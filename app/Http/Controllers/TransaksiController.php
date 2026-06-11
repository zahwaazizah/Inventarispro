<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // Form peminjaman (dari scan QR atau manual)
    public function formPeminjaman(Request $request)
    {
        if (Auth::user()->role != 'petugas') {
            abort(403);
        }
        
        $barangFromQR = null;
        $barangs = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();
        
        // Jika dari scan QR
        if ($request->has('qr')) {
            $hash = $request->qr;
            $barangFromQR = Barang::where('qr_code_hash', $hash)->first();
            
            if (!$barangFromQR) {
                return redirect()->route('qr.scan')->with('error', 'QR Code tidak valid!');
            }
        }
        
        return view('transaksi.peminjaman', compact('barangs', 'barangFromQR'));
    }
    
    // Daftar peminjaman aktif
    public function index()
    {
        if (Auth::user()->role != 'petugas') {
            abort(403);
        }
        
        $transaksis = Transaksi::with(['barang'])
            ->where('status', 'dipinjam')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('transaksi.index', compact('transaksis'));
    }
    
    // Proses peminjaman
    public function storePinjam(Request $request)
    {
        if (Auth::user()->role != 'petugas') {
            abort(403);
        }
        
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'peminjam' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'keterangan' => 'nullable|string'
        ]);
        
        $barang = Barang::find($validated['barang_id']);
        
        // Validasi stok
        if ($barang->stok < $validated['jumlah']) {
            return redirect()->back()
                ->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $barang->stok)
                ->withInput();
        }
        
        if ($barang->stok <= 0) {
            return redirect()->back()
                ->with('error', 'Stok barang habis! Tidak dapat melakukan peminjaman.')
                ->withInput();
        }
        
        // Generate kode transaksi unik
        $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(uniqid());
        
        Transaksi::create([
            'kode_transaksi' => $kodeTransaksi,
            'item_id' => $validated['barang_id'],
            'peminjam' => $validated['peminjam'],
            'jumlah' => $validated['jumlah'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'status' => 'dipinjam',
            'keterangan' => $validated['keterangan'] ?? null,
            'created_by' => auth()->id()
        ]);
        
        // Kurangi stok barang
        $barang->stok -= $validated['jumlah'];
        $barang->save();
        
        return redirect()->route('transaksi.index')
            ->with('success', 'Peminjaman berhasil! Kode Transaksi: ' . $kodeTransaksi);
    }
    
    // Form pengembalian
    public function formKembali($id)
    {
        if (Auth::user()->role != 'petugas') {
            abort(403);
        }
        
        $transaksi = Transaksi::with(['barang'])->findOrFail($id);
        
        if ($transaksi->status == 'dikembalikan') {
            return redirect()->route('transaksi.index')
                ->with('error', 'Barang sudah dikembalikan sebelumnya.');
        }
        
        return view('transaksi.kembali', compact('transaksi'));
    }
    
    // Proses pengembalian
    public function processKembali(Request $request, $id)
    {
        if (Auth::user()->role != 'petugas') {
            abort(403);
        }
        
        $transaksi = Transaksi::findOrFail($id);
        
        if ($transaksi->status == 'dikembalikan') {
            return redirect()->route('transaksi.index')
                ->with('error', 'Barang sudah dikembalikan sebelumnya.');
        }
        
        $validated = $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi' => 'nullable|string',
            'keterangan' => 'nullable|string'
        ]);
        
        $transaksi->update([
            'status' => 'dikembalikan',
            'tanggal_kembali_aktual' => $validated['tanggal_kembali_aktual'],
            'kondisi' => $validated['kondisi'] ?? 'baik',
            'keterangan' => $validated['keterangan'] ?? $transaksi->keterangan
        ]);
        
        // Tambah stok kembali
        $barang = Barang::find($transaksi->item_id);
        $barang->stok += $transaksi->jumlah;
        $barang->save();
        
        $isTerlambat = $transaksi->tanggal_kembali && now()->gt($transaksi->tanggal_kembali);
        
        $message = 'Barang berhasil dikembalikan.';
        if ($isTerlambat) {
            $message .= ' (Terlambat dari jadwal)';
        }
        
        return redirect()->route('transaksi.index')->with('success', $message);
    }
}