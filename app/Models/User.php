<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasName,  FilamentUser
{
    use HasUuids, HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lastname',
        'firstname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // return str_ends_with($this->email, 'vidalmat06@gmail.com') && $this->hasVerifiedEmail();
        return true;
    }

    public function getFilamentName(): string
    {
        // return $this->getAttributeValue('lastname');
        return "{$this->firstname} {$this->lastname}";
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    public function isManager(): bool
    {
        return $this->hasRole('Manager');
    }

    public function error_logs(): BelongsTo
    {
        return $this->belongsTo(ErrorLog::class);
    }
}
