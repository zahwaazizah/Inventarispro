<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data yang sama untuk semua role
        $totalBarang = Barang::count();
        $barangTersedia = Barang::where('stok', '>', 0)->count();
        $barangDipinjam = Transaksi::where('status', 'dipinjam')->count();
        $barangTerlambat = Transaksi::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<', now())
            ->count();
        
        $barangHampirHabis = Barang::where('stok', '<=', 3)
            ->where('stok', '>', 0)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();
        
        // Jika ADMIN
        if ($user->role == 'admin') {
            $peminjamanTerbaru = Transaksi::with(['barang'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            $transaksiTerlambat = Transaksi::with(['barang'])
                ->where('status', 'dipinjam')
                ->whereDate('tanggal_kembali', '<', now())
                ->orderBy('tanggal_kembali', 'asc')
                ->get();
            
            $totalKategori = Kategori::count();
            $totalLokasi = Lokasi::count();
            $totalPetugas = User::count();
            $totalQrCode = Barang::whereNotNull('qr_code_hash')->count();
            
            return view('dashboard.index', compact(
                'totalBarang',
                'barangTersedia',
                'barangDipinjam',
                'barangTerlambat',
                'barangHampirHabis',
                'peminjamanTerbaru',
                'transaksiTerlambat',
                'totalKategori',
                'totalLokasi',
                'totalPetugas',
                'totalQrCode'
            ));
        }
        
        // Jika PETUGAS
        $peminjamanAktif = Transaksi::with(['barang'])
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_kembali', 'asc')
            ->limit(10)
            ->get();
        
        $riwayatTerbaru = Transaksi::with(['barang'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.petugas', compact(
            'totalBarang',
            'barangTersedia',
            'barangDipinjam',
            'barangTerlambat',
            'barangHampirHabis',
            'peminjamanAktif',
            'riwayatTerbaru'
        ));
    }
}