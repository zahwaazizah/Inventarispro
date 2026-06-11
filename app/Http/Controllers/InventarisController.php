<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'lokasi']);
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('kode_inventaris', 'LIKE', "%{$search}%")
                  ->orWhere('nama_barang', 'LIKE', "%{$search}%");
        }
        $barangs = $query->paginate(10);

        // Statistik
        $totalBarang = Barang::count();
        $totalTersedia = Barang::where('stok', '>', 0)->count();
        $totalMenipis = Barang::where('stok', '<=', 3)->where('stok', '>', 0)->count();

        return view('inventaris.index', compact('barangs', 'totalBarang', 'totalTersedia', 'totalMenipis'));
    }

public function create()
{
    if (Auth::user()->role != 'admin') abort(403);
    $kategoris = Kategori::all();
    $lokasis = Lokasi::all();
    return view('inventaris.create', compact('kategoris', 'lokasis'));
}

public function store(Request $request)
{
    if (Auth::user()->role != 'admin') abort(403);

    $validated = $request->validate([
        'kode_inventaris' => 'required|unique:barangs',
        'nama_barang' => 'required',
        'kategori_id' => 'required|exists:kategoris,id',
        'lokasi_id' => 'required|exists:lokasis,id',
        'merk' => 'nullable|string',
        'serial_number' => 'nullable|string',
        'spesifikasi' => 'nullable|string',
        'stok' => 'nullable|integer|min:0',
        'status_barang' => 'nullable|string',
        'kondisi_barang' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'tahun_pembelian' => 'nullable|integer',
        'harga_pembelian' => 'nullable|numeric',
    ]);

    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/barang'), $filename);
        $validated['foto'] = 'uploads/barang/' . $filename;
    }

    Barang::create($validated);
    return redirect()->route('inventaris.index')->with('success', 'Barang berhasil ditambahkan');
}

    public function show($id)
    {
        $barang = Barang::with(['kategori', 'lokasi'])->findOrFail($id);
        $riwayats = Transaksi::where('item_id', $id)->orderBy('created_at', 'desc')->paginate(10);
        return view('inventaris.show', compact('barang', 'riwayats'));
    }

    public function edit($id)
    {
        if (Auth::user()->role != 'admin') abort(403);
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::all();
        $lokasis = Lokasi::all();
        return view('inventaris.edit', compact('barang', 'kategoris', 'lokasis'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role != 'admin') abort(403);
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'kode_inventaris' => 'required|unique:barangs,kode_inventaris,' . $id,
            'nama_barang' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'lokasi_id' => 'required|exists:lokasis,id',
            'merk' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'spesifikasi' => 'nullable|string',
            'stok' => 'nullable|integer|min:0',
            'status_barang' => 'nullable|string',
            'kondisi_barang' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tahun_pembelian' => 'nullable|integer',
            'harga_pembelian' => 'nullable|numeric',
            'masa_garansi' => 'nullable|string',
            'sumber_dana' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            if ($barang->foto && file_exists(public_path($barang->foto))) {
                unlink(public_path($barang->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/barang'), $filename);
            $validated['foto'] = 'uploads/barang/' . $filename;
        }

        $barang->update($validated);
        return redirect()->route('inventaris.index')->with('success', 'Barang berhasil diupdate');
    }

    public function destroy($id)
    {
        if (Auth::user()->role != 'admin') abort(403);
        $barang = Barang::findOrFail($id);
        if ($barang->foto && file_exists(public_path($barang->foto))) {
            unlink(public_path($barang->foto));
        }
        $barang->delete();
        return redirect()->route('inventaris.index')->with('success', 'Barang berhasil dihapus');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $barangs = Barang::with(['kategori', 'lokasi'])
            ->where('kode_inventaris', 'LIKE', "%{$search}%")
            ->orWhere('nama_barang', 'LIKE', "%{$search}%")
            ->paginate(10);
        return view('inventaris.index', compact('barangs'));
    }
}