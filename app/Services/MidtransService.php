<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Collection;

class MidtransService
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    public function createSnapToken(Order $order): string
    {
        $user     = $order->user;
        $training = $order->training;

        $params = [
            'transaction_details' => [
                'order_id'     => $order->midtrans_order_id,
                'gross_amount' => (int) $order->amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
            ],
            'item_details' => [
                [
                    'id'       => (string) $training->id,
                    'price'    => (int) $order->amount,
                    'quantity' => 1,
                    'name'     => substr($training->title, 0, 50),
                ],
            ],
        ];

        return \Midtrans\Snap::getSnapToken($params);
    }

    /**
     * Snap token for a batch — multiple orders sharing the same midtrans_order_id.
     * Each order's training becomes one item_details row.
     */
    public function createBatchSnapToken(Collection $orders): string
    {
        $first = $orders->first();
        $user  = $first->user;

        $items = $orders->map(fn (Order $o) => [
            'id'       => (string) $o->training_id,
            'price'    => (int) $o->amount,
            'quantity' => 1,
            'name'     => substr($o->training->title, 0, 50),
        ])->values()->all();

        $params = [
            'transaction_details' => [
                'order_id'     => $first->midtrans_order_id,
                'gross_amount' => (int) $orders->sum('amount'),
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
            ],
            'item_details' => $items,
        ];

        return \Midtrans\Snap::getSnapToken($params);
    }

    public function handleNotification(array $payload): array
    {
        $notif = new \Midtrans\Notification();
        return (array) $notif;
    }
}
