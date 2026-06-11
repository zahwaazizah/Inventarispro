<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi
    public function barangsCreated()
    {
        return $this->hasMany(Barang::class, 'created_by');
    }

    public function barangsUpdated()
    {
        return $this->hasMany(Barang::class, 'updated_by');
    }

    public function riwayats()
    {
        return $this->hasMany(Riwayat::class, 'user_id');
    }
}