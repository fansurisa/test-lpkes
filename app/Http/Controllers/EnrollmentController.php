<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Training;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    public function checkout(Training $training)
    {
        $this->authorize('create', Enrollment::class);

        if (auth()->user()->isEnrolled($training)) {
            return redirect()->route('catalog.show', $training->slug)
                ->with('info', 'Anda sudah terdaftar di pelatihan ini.');
        }

        if (in_array($training->event_status, ['selesai', 'ditutup'], true)) {
            return redirect()->route('catalog.show', $training->slug)
                ->with('error', $training->event_status === 'selesai'
                    ? 'Pelatihan ini telah selesai.'
                    : 'Pendaftaran pelatihan ini sudah ditutup.');
        }

        if ($training->isFull()) {
            return redirect()->route('catalog.show', $training->slug)
                ->with('error', 'Maaf, kuota pelatihan ini sudah penuh.');
        }

        if ($training->is_free) {
            return $this->enrollFree($training);
        }

        return view('enrollments.checkout', compact('training'));
    }

    private function enrollFree(Training $training)
    {
        DB::transaction(function () use ($training) {
            $enrollment = Enrollment::create([
                'user_id'     => auth()->id(),
                'training_id' => $training->id,
                'status'      => 'active',
                'enrolled_at' => now(),
            ]);
        });

        return redirect()->route('enrollments.success', $training)
            ->with('success', 'Pendaftaran berhasil! Selamat belajar.');
    }

    public function pay(Request $request, Training $training)
    {
        $this->authorize('create', Enrollment::class);

        if (auth()->user()->isEnrolled($training)) {
            return redirect()->route('dashboard');
        }

        DB::transaction(function () use ($training, &$order) {
            $enrollment = Enrollment::create([
                'user_id'     => auth()->id(),
                'training_id' => $training->id,
                'status'      => 'pending',
                'enrolled_at' => now(),
            ]);

            $order = Order::create([
                'user_id'       => auth()->id(),
                'training_id'   => $training->id,
                'enrollment_id' => $enrollment->id,
                'amount'        => $training->price,
                'status'        => 'pending',
            ]);

            // Try to create Midtrans Snap token; skip if keys not configured (dev mode)
            try {
                if (config('midtrans.server_key')) {
                    $snapToken = $this->midtrans->createSnapToken($order);
                    $order->update(['snap_token' => $snapToken]);
                }
            } catch (\Throwable $e) {
                logger()->warning('Midtrans snap token failed: ' . $e->getMessage());
            }
        });

        return view('enrollments.payment', compact('training', 'order'));
    }

    public function skipPayment(Training $training)
    {
        abort_if(app()->isProduction(), 403);

        $order = Order::where('user_id', auth()->id())
            ->where('training_id', $training->id)
            ->where('status', 'pending')
            ->latest()
            ->firstOrFail();

        $order->update([
            'status'         => 'paid',
            'payment_method' => 'dev_skip',
            'paid_at'        => now(),
        ]);

        $order->enrollment?->update(['status' => 'active']);

        return redirect()->route('enrollments.success', $training)
            ->with('success', 'Pembayaran (DEV SKIP) berhasil. Akses pelatihan sudah aktif.');
    }

    public function success(Training $training)
    {
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('training_id', $training->id)
            ->with('order')
            ->firstOrFail();

        $order = $enrollment->order;

        return view('enrollments.success', compact('training', 'enrollment', 'order'));
    }

    public function notification(Request $request)
    {
        $payload = $this->midtrans->handleNotification($request->all());

        $orders = Order::where('midtrans_order_id', $payload['order_id'])->get();
        abort_if($orders->isEmpty(), 404);

        $status = $payload['transaction_status'];

        DB::transaction(function () use ($orders, $payload, $status) {
            foreach ($orders as $order) {
                if (in_array($status, ['capture', 'settlement'])) {
                    $order->update([
                        'status'                  => 'paid',
                        'payment_method'          => $payload['payment_type'] ?? null,
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                        'paid_at'                 => now(),
                        'midtrans_response'       => $payload,
                    ]);
                    $order->enrollment?->update(['status' => 'active']);
                } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                    $order->update([
                        'status'            => 'failed',
                        'midtrans_response' => $payload,
                    ]);
                }
            }
        });

        return response()->json(['status' => 'ok']);
    }
}
