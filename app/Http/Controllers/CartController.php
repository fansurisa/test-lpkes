<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Training;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    public function index()
    {
        $items = CartItem::with(['training.category'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $enrolledIds = auth()->user()
            ->enrollments()
            ->pluck('training_id')
            ->toArray();

        $total = $items->sum(fn ($item) => $item->training->price ?? 0);

        return view('cart.index', compact('items', 'enrolledIds', 'total'));
    }

    public function add(Training $training)
    {
        if (! $training->is_published) {
            return back()->with('error', 'Pelatihan tidak tersedia.');
        }

        if (auth()->user()->isEnrolled($training)) {
            return back()->with('info', 'Anda sudah terdaftar di pelatihan ini.');
        }

        CartItem::firstOrCreate([
            'user_id'     => auth()->id(),
            'training_id' => $training->id,
        ]);

        return back()->with('success', 'Pelatihan berhasil ditambahkan ke keranjang.');
    }

    public function remove(Training $training)
    {
        CartItem::where('user_id', auth()->id())
            ->where('training_id', $training->id)
            ->delete();

        return back()->with('success', 'Pelatihan dihapus dari keranjang.');
    }

    public function clear()
    {
        CartItem::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Keranjang dikosongkan.');
    }

    /**
     * Review page — single combined checkout for all valid items in cart.
     */
    public function checkout()
    {
        $this->authorize('create', Enrollment::class);

        $items = $this->validCartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Tidak ada pelatihan yang dapat dibayar di keranjang Anda.');
        }

        $payable  = $items->reject(fn ($i) => $i->training->is_free);
        $freeOnly = $items->filter(fn ($i) => $i->training->is_free);
        $total    = (int) $payable->sum(fn ($i) => $i->training->price);

        return view('cart.checkout', [
            'items'    => $items,
            'payable'  => $payable,
            'freeOnly' => $freeOnly,
            'total'    => $total,
        ]);
    }

    /**
     * Create enrollments + (paid) orders for all valid cart items, then either
     *   - render Snap payment page if there's a balance to pay, or
     *   - redirect straight to success if everything was free.
     */
    public function pay()
    {
        $this->authorize('create', Enrollment::class);

        $items = $this->validCartItems();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Tidak ada pelatihan yang dapat dibayar di keranjang Anda.');
        }

        $batchId = (string) Str::uuid();
        $midtransOrderId = 'LPK-' . strtoupper(Str::random(10)) . '-' . now()->format('YmdHis');

        $orders   = collect();
        $training = null;

        DB::transaction(function () use ($items, $batchId, $midtransOrderId, &$orders) {
            foreach ($items as $item) {
                $training = $item->training;

                $enrollment = Enrollment::create([
                    'user_id'     => auth()->id(),
                    'training_id' => $training->id,
                    'status'      => $training->is_free ? 'active' : 'pending',
                    'enrolled_at' => now(),
                ]);

                if (! $training->is_free) {
                    $order = Order::create([
                        'user_id'           => auth()->id(),
                        'training_id'       => $training->id,
                        'enrollment_id'     => $enrollment->id,
                        'amount'            => $training->price,
                        'status'            => 'pending',
                        'midtrans_order_id' => $midtransOrderId,
                    ]);
                    $orders->push($order->fresh('training', 'user'));
                }
            }

            // Free-only checkout: clear cart now (paid flow clears on success).
            if ($orders->isEmpty()) {
                CartItem::where('user_id', auth()->id())->delete();
            }
        });

        if ($orders->isEmpty()) {
            return redirect()->route('cart.success', ['batch' => $batchId])
                ->with('success', 'Pendaftaran berhasil! Selamat belajar.');
        }

        $snapToken = null;
        try {
            if (config('midtrans.server_key')) {
                $snapToken = $this->midtrans->createBatchSnapToken($orders);
                Order::whereIn('id', $orders->pluck('id'))->update(['snap_token' => $snapToken]);
            }
        } catch (\Throwable $e) {
            logger()->warning('Midtrans batch snap token failed: ' . $e->getMessage());
        }

        return view('cart.payment', [
            'orders'          => $orders,
            'snapToken'       => $snapToken,
            'midtransOrderId' => $midtransOrderId,
            'total'           => (int) $orders->sum('amount'),
        ]);
    }

    /**
     * Dev-only: skip the Midtrans round trip and mark all batch orders paid.
     */
    public function skipPay(Request $request)
    {
        abort_if(app()->isProduction(), 403);

        $request->validate(['midtrans_order_id' => 'required|string']);

        $orders = Order::with('enrollment')
            ->where('user_id', auth()->id())
            ->where('midtrans_order_id', $request->midtrans_order_id)
            ->where('status', 'pending')
            ->get();

        abort_if($orders->isEmpty(), 404);

        DB::transaction(function () use ($orders) {
            foreach ($orders as $order) {
                $order->update([
                    'status'         => 'paid',
                    'payment_method' => 'dev_skip',
                    'paid_at'        => now(),
                ]);
                $order->enrollment?->update(['status' => 'active']);
            }
            CartItem::where('user_id', auth()->id())->delete();
        });

        return redirect()->route('cart.success', ['batch' => $request->midtrans_order_id])
            ->with('success', 'Pembayaran (DEV SKIP) berhasil. Semua pelatihan sudah aktif.');
    }

    public function success(Request $request)
    {
        $batch = $request->query('batch');

        $orders = Order::with(['training', 'enrollment'])
            ->where('user_id', auth()->id())
            ->where('midtrans_order_id', $batch)
            ->get();

        $freeEnrollments = collect();
        if ($orders->isEmpty()) {
            // Pure-free batch: pull recent active enrollments from the last few minutes.
            $freeEnrollments = Enrollment::with('training')
                ->where('user_id', auth()->id())
                ->where('enrolled_at', '>=', now()->subMinutes(5))
                ->whereDoesntHave('order')
                ->latest('enrolled_at')
                ->get();
        }

        return view('cart.success', compact('orders', 'freeEnrollments'));
    }

    /**
     * Cart items that are still purchasable: published, not enrolled, has open registration, has stock.
     */
    private function validCartItems(): Collection
    {
        $items = CartItem::with('training')
            ->where('user_id', auth()->id())
            ->get();

        $enrolledIds = auth()->user()->enrollments()->pluck('training_id')->toArray();

        return $items->filter(function (CartItem $item) use ($enrolledIds) {
            $t = $item->training;
            if (! $t || ! $t->is_published)                   return false;
            if (in_array($t->id, $enrolledIds, true))         return false;
            if ($t->isFull())                                 return false;
            if (in_array($t->event_status, ['selesai', 'ditutup'], true)) return false;
            return true;
        })->values();
    }
}
