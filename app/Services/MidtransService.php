<?php

namespace App\Services;

use App\Models\Order;

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

    public function handleNotification(array $payload): array
    {
        $notif = new \Midtrans\Notification();
        return (array) $notif;
    }
}
