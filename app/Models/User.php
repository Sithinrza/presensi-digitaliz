<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,    // Model tujuan
            'role_user',    // Nama tabel pivot (jembatan)
            'user_id',      // Foreign key untuk User
            'role_id'       // Foreign key untuk Role
        );
    }

    public function hasRole(string $roleName): bool
    {
        // Loop semua role yang dimiliki user ini
        foreach ($this->roles as $role) {
            // Jika salah satu nama role-nya cocok, kembalikan true
            if ($role->name === $roleName) {
                return true;
            }
        }
        // Jika tidak ada yang cocok, kembalikan false
        return false;
    }

    public function karyawan(): HasOne
    {
        // Asumsi: foreign key di tabel karyawans adalah 'user_id'
        // dan primary key di tabel users adalah 'id'
        return $this->hasOne(Karyawan::class, 'user_id', 'id');
    }
}
