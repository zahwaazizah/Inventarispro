<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index()
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $kategoris = Kategori::all();
        $totalBarang = Barang::count();
        
        return view('kategori.index', compact('kategoris', 'totalBarang'));
    }
    
    public function store(Request $request)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris',
            'deskripsi' => 'nullable|string'
        ]);
        
        Kategori::create($request->all());
        
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }
    
    public function update(Request $request, $id)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,' . $id,
            'deskripsi' => 'nullable|string'
        ]);
        
        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());
        
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate');
    }
    
    public function destroy($id)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $kategori = Kategori::findOrFail($id);
        
        $barangCount = Barang::where('kategori_id', $id)->count();
        if ($barangCount > 0) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $barangCount . ' barang');
        }
        
        $kategori->delete();
        
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}