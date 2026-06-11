<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LokasiController extends Controller
{
    public function index()
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $lokasis = Lokasi::all();
        $totalBarang = Barang::count();
        
        return view('lokasi.index', compact('lokasis', 'totalBarang'));
    }
    
    public function store(Request $request)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $request->validate([
            'nama_lokasi' => 'required|string|max:100|unique:lokasis',
            'deskripsi' => 'nullable|string'
        ]);
        
        Lokasi::create($request->all());
        
        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil ditambahkan');
    }
    
    public function update(Request $request, $id)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $request->validate([
            'nama_lokasi' => 'required|string|max:100|unique:lokasis,nama_lokasi,' . $id,
            'deskripsi' => 'nullable|string'
        ]);
        
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update($request->all());
        
        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil diupdate');
    }
    
    public function destroy($id)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $lokasi = Lokasi::findOrFail($id);
        
        $barangCount = Barang::where('lokasi_id', $id)->count();
        if ($barangCount > 0) {
            return redirect()->route('lokasi.index')->with('error', 'Lokasi tidak dapat dihapus karena masih digunakan oleh ' . $barangCount . ' barang');
        }
        
        $lokasi->delete();
        
        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil dihapus');
    }
}