<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'type', 'category_id', 'description', 'objectives',
        'thumbnail', 'price', 'is_free', 'skp_value', 'pelataran_link',
        'schedule', 'duration', 'max_participants', 'is_published',
        'trainer_name', 'trainer_title', 'trainer_avatar', 'trainer_bio',
    ];

    protected function casts(): array
    {
        return [
            'is_free'        => 'boolean',
            'is_published'   => 'boolean',
            'price'          => 'decimal:2',
            'skp_value'      => 'integer',
            'schedule'       => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Training $training) {
            if (empty($training->slug)) {
                $training->slug = Str::slug($training->title);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function skpRecords(): HasMany
    {
        return $this->hasMany(SkpRecord::class);
    }

    public function hasSkp(): bool
    {
        return $this->skp_value > 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->is_free) {
            return 'GRATIS';
        }
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return str_starts_with($this->thumbnail, 'http')
                ? $this->thumbnail
                : asset('storage/' . $this->thumbnail);
        }
        return asset('images/training-placeholder.jpg');
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'event' ? 'Event' : 'E-Course';
    }

    public function getEnrollmentCountAttribute(): int
    {
        return $this->enrollments()->count();
    }

    public function isFull(): bool
    {
        if (!$this->max_participants) {
            return false;
        }
        return $this->enrollments()->count() >= $this->max_participants;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
