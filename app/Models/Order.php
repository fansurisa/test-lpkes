<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'training_id', 'enrollment_id', 'amount', 'status',
        'midtrans_order_id', 'midtrans_transaction_id', 'payment_method',
        'payment_type', 'snap_token', 'paid_at', 'midtrans_response',
    ];

    protected function casts(): array
    {
        return [
            'amount'             => 'decimal:2',
            'paid_at'            => 'datetime',
            'midtrans_response'  => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->midtrans_order_id)) {
                $order->midtrans_order_id = 'LPK-' . strtoupper(Str::random(10)) . '-' . now()->format('YmdHis');
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu Pembayaran',
            'paid'      => 'Dibayar',
            'failed'    => 'Gagal',
            'cancelled' => 'Dibatalkan',
            default     => $this->status,
        };
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
