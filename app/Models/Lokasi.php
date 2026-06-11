<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasis';

    protected $fillable = [
        'nama_lokasi',
        'deskripsi',
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'lokasi_id');
    }
}