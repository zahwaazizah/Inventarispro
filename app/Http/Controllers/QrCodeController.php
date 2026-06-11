<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    // Halaman kelola QR Code (Admin & Petugas)
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'lokasi']);
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('kode_inventaris', 'LIKE', "%{$search}%")
                  ->orWhere('nama_barang', 'LIKE', "%{$search}%");
        }
        
        $barangs = $query->paginate(10);
        
        $selected_barang = null;
        if ($request->has('id')) {
            $selected_barang = Barang::with(['kategori', 'lokasi'])->find($request->id);
        }
        
        return view('qr.index', compact('barangs', 'selected_barang'));
    }
    
    // Generate QR Code baru
    public function generate($id)
    {
        $barang = Barang::findOrFail($id);
        
        $hash = md5($barang->id . time() . uniqid());
        
        $barang->qr_code_hash = $hash;
        $barang->save();
        
        return redirect()->route('qr.index', ['id' => $barang->id])
            ->with('success', 'QR Code berhasil dibuat untuk ' . $barang->nama_barang);
    }
    
    // Refresh QR Code (buat ulang)
    public function refresh($id)
    {
        $barang = Barang::findOrFail($id);
        
        $hash = md5($barang->id . time() . uniqid());
        
        $barang->qr_code_hash = $hash;
        $barang->save();
        
        return redirect()->route('qr.index', ['id' => $barang->id])
            ->with('success', 'QR Code berhasil di-refresh untuk ' . $barang->nama_barang);
    }
    
    // Download QR Code sebagai PNG
    public function download($id)
    {
        $barang = Barang::findOrFail($id);
        
        if (!$barang->qr_code_hash) {
            return redirect()->route('qr.index')->with('error', 'QR Code belum tersedia');
        }
        
        // Gunakan URL publik yang benar (dengan subfolder /inventarispro)
        $url = url('/scan/' . $barang->qr_code_hash);
        $qrCode = QrCode::format('png')->size(300)->generate($url);
        
        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="QR_' . $barang->kode_inventaris . '.png"');
    }
    
    // Ambil data QR dalam format JSON (untuk preview)
    public function getJson($id)
    {
        $barang = Barang::with(['kategori', 'lokasi'])->findOrFail($id);
        
        if (!$barang->qr_code_hash) {
            return response()->json(['success' => false, 'message' => 'QR Code belum tersedia']);
        }
        
        $url = url('/scan/' . $barang->qr_code_hash);
        $qrImage = QrCode::format('png')->size(200)->generate($url);
        $qrImageBase64 = 'data:image/png;base64,' . base64_encode($qrImage);
        
        return response()->json([
            'success' => true,
            'qr_image' => $qrImageBase64,
            'qr_url' => $url,
            'barang' => [
                'id' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'kode_inventaris' => $barang->kode_inventaris,
                'kategori' => $barang->kategori->nama_kategori ?? '-',
                'lokasi' => $barang->lokasi->nama_lokasi ?? '-',
                'stok' => $barang->stok
            ]
        ]);
    }
    
    // Hapus QR Code
    public function destroyQr($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->qr_code_hash = null;
        $barang->save();
        
        return redirect()->route('qr.index')->with('success', 'QR Code berhasil dihapus');
    }
    
    // Halaman scan QR Code
    public function showScanPage()
    {
        return view('qr.scan');
    }
    
    // Proses scan QR Code (redirect ke form peminjaman)
    public function processScan(Request $request)
    {
        $qrString = $request->input('qr_string');
        
        // Jika qrString berupa URL lengkap, ekstrak hash-nya
        if (filter_var($qrString, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($qrString);
            $path = $parsed['path'] ?? '';
            // Ambil bagian terakhir dari path (contoh: .../scan/abcd1234 -> abcd1234)
            $hash = basename($path);
        } else {
            $hash = $qrString;
        }
        
        $barang = Barang::where('qr_code_hash', $hash)->first();
        
        // Untuk AJAX request (dari scanner real-time)
        if ($request->ajax()) {
            if ($barang) {
                return response()->json([
                    'success' => true,
                    'message' => 'Barang ditemukan!',
                    'data' => [
                        'id' => $barang->id,
                        'kode_inventaris' => $barang->kode_inventaris,
                        'nama_barang' => $barang->nama_barang,
                        'kategori' => $barang->kategori->nama_kategori ?? '-',
                        'lokasi' => $barang->lokasi->nama_lokasi ?? '-',
                        'stok' => $barang->stok
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau barang tidak ditemukan!'
                ]);
            }
        }
        
        // Untuk form submit manual (bukan AJAX)
        if ($barang) {
            // Redirect ke form peminjaman dengan parameter hash (bukan URL)
            return redirect()->route('transaksi.peminjaman.form', ['qr' => $hash])
                ->with('success', 'Barang ditemukan! Silakan lengkapi data peminjaman.');
        } else {
            return redirect()->route('qr.scan')->with('error', 'QR Code tidak valid!');
        }
    }
    
    // Halaman publik untuk scan QR (tanpa login) - menampilkan detail barang
    public function publicShow($hash)
    {
        $barang = Barang::where('qr_code_hash', $hash)->firstOrFail();
        return view('qr.public-show', compact('barang'));
    }
}