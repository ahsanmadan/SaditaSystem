<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_admin', 'notifications_seen_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // ─── Role Constants ──────────────────────────────────────────────────────
    const ROLE_OWNER = 'owner';

    const ROLE_ADMIN = 'admin';

    const ROLE_STAFF = 'staff';

    // ─── Role Helpers ─────────────────────────────────────────────────────────

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN]);
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    /**
     * Akses ke fitur destructive (delete, force-delete, verifikasi uang).
     * Owner dan Admin bisa. Staff tidak.
     */
    public function canDoOperasional(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Akses penuh ke semua menu termasuk keuangan sensitif.
     * Hanya Owner.
     */
    public function canAccessKeuangan(): bool
    {
        return $this->isOwner();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'notifications_seen_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
}
