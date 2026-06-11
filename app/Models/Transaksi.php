<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'item_id', 'peminjam', 'jumlah', 'tanggal_pinjam',
        'tanggal_kembali', 'status', 'keterangan', 'created_by'
    ];
    
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'created_at' => 'datetime',
    ];
    
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'item_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function getTanggalPinjamFormattedAttribute()
    {
        return $this->tanggal_pinjam ? date('d/m/Y', strtotime($this->tanggal_pinjam)) : '-';
    }
    
    public function getTanggalKembaliFormattedAttribute()
    {
        return $this->tanggal_kembali ? date('d/m/Y', strtotime($this->tanggal_kembali)) : '-';
    }
    
    // Accessor (bisa diakses sebagai properti $transaksi->is_terlambat)
    public function getIsTerlambatAttribute()
    {
        if ($this->status == 'dikembalikan') return false;
        return $this->tanggal_kembali && now()->gt($this->tanggal_kembali);
    }
    
    // ========== TAMBAHAN: method isTerlambat() ==========
    public function isTerlambat()
    {
        if ($this->status == 'dikembalikan') return false;
        return $this->tanggal_kembali && now()->gt($this->tanggal_kembali);
    }
    // ===================================================
    
    public function scopeAktif($query)
    {
        return $query->where('status', 'dipinjam');
    }
    
    public function scopeSelesai($query)
    {
        return $query->where('status', 'dikembalikan');
    }
}