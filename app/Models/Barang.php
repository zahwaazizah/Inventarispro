<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barangs';

    protected $fillable = [
        'kode_inventaris',
        'kategori_id',
        'lokasi_id',
        'nama_barang',
        'merk',
        'serial_number',
        'spesifikasi',
        'tahun_pembelian',
        'harga_pembelian',
        'masa_garansi',
        'sumber_dana',
        'status_barang',
        'kondisi_barang',
        'stok',
        'qr_code_hash',
        'foto',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tahun_pembelian' => 'integer',
        'harga_pembelian' => 'decimal:2',
        'masa_garansi' => 'date',
        'stok' => 'integer',
    ];

    // Relasi
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function riwayats()
    {
        return $this->hasMany(Riwayat::class, 'barang_id');
    }
}