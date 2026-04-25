<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin');
    }

    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'phone',
        'user_type',
        'str_number',
        'profession',
        'avatar',
        'profile_completed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'     => 'datetime',
            'profile_completed_at'  => 'datetime',
            'password'              => 'hashed',
        ];
    }

    public function isNakes(): bool
    {
        return $this->user_type === 'nakes';
    }

    public function isProfileCompleted(): bool
    {
        return $this->profile_completed_at !== null;
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

    public function totalSkp(): int
    {
        return $this->skpRecords()->sum('skp_earned');
    }

    public function isEnrolled(Training $training): bool
    {
        return $this->enrollments()->where('training_id', $training->id)->exists();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return str_starts_with($this->avatar, 'http')
                ? $this->avatar
                : asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=0ea5e9&background=e0f2fe';
    }

    public function getProfessionLabelAttribute(): string
    {
        return match ($this->profession) {
            'dokter'      => 'Dokter',
            'perawat'     => 'Perawat',
            'bidan'       => 'Bidan',
            'apoteker'    => 'Apoteker',
            'analis'      => 'Analis Kesehatan',
            'radiografer' => 'Radiografer',
            'fisioterapis'=> 'Fisioterapis',
            'gizi'        => 'Ahli Gizi',
            'rekam_medis' => 'Rekam Medis',
            'lainnya'     => 'Lainnya',
            default       => $this->profession ?? '-',
        };
    }
}
