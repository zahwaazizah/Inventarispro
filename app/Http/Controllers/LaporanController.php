<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalTransaksi = Transaksi::count();
        $sedangDipinjam = Transaksi::where('status', 'dipinjam')->count();
        $persentaseTerpakai = $totalBarang > 0 ? round(($sedangDipinjam / $totalBarang) * 100) : 0;
        
        $kategoris = Kategori::all();
        $lokasis = Lokasi::all();
        
        return view('laporan.index', compact(
            'totalBarang', 'totalTransaksi', 'sedangDipinjam', 'persentaseTerpakai',
            'kategoris', 'lokasis'
        ));
    }
    
    public function laporanInventaris()
    {
        $barangs = Barang::with(['kategori', 'lokasi'])->get();
        return view('laporan.inventaris', compact('barangs'));
    }
    
    public function laporanTransaksi()
    {
        $transaksis = Transaksi::with(['barang'])->orderBy('created_at', 'desc')->get();
        return view('laporan.transaksi', compact('transaksis'));
    }
    
    public function exportExcel(Request $request)
    {
        $type = $request->query('type', 'all');
        $id = $request->query('id');
        
        switch ($type) {
            case 'inventaris':
                $barangs = Barang::with(['kategori', 'lokasi'])->get();
                $title = 'Laporan Inventaris Barang';
                $view = 'laporan.excel_inventaris';
                $data = compact('barangs', 'title');
                break;
            case 'transaksi':
                $transaksis = Transaksi::with(['barang'])->orderBy('created_at', 'desc')->get();
                $title = 'Laporan Transaksi';
                $view = 'laporan.excel_transaksi';
                $data = compact('transaksis', 'title');
                break;
            case 'kategori':
                if (!$id) return back()->with('error', 'ID kategori tidak ditemukan');
                $kategori = Kategori::find($id);
                if (!$kategori) return back()->with('error', 'Kategori tidak ditemukan');
                $barangs = Barang::where('kategori_id', $id)->with(['kategori', 'lokasi'])->get();
                $title = 'Laporan Barang per Kategori: ' . $kategori->nama_kategori;
                $view = 'laporan.excel_inventaris';
                $data = compact('barangs', 'title');
                break;
            case 'lokasi':
                if (!$id) return back()->with('error', 'ID lokasi tidak ditemukan');
                $lokasi = Lokasi::find($id);
                if (!$lokasi) return back()->with('error', 'Lokasi tidak ditemukan');
                $barangs = Barang::where('lokasi_id', $id)->with(['kategori', 'lokasi'])->get();
                $title = 'Laporan Barang per Lokasi: ' . $lokasi->nama_lokasi;
                $view = 'laporan.excel_inventaris';
                $data = compact('barangs', 'title');
                break;
            case 'all':
            default:
                $barangs = Barang::with(['kategori', 'lokasi'])->get();
                $title = 'Laporan Semua Data (Inventaris)';
                $view = 'laporan.excel_inventaris';
                $data = compact('barangs', 'title');
                break;
        }
        
        $html = view($view, $data)->render();
        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $title . '_' . date('Y-m-d') . '.xls"'
        ]);
    }
    
    public function exportPdf(Request $request)
    {
        $type = $request->query('type', 'all');
        $id = $request->query('id');
        
        switch ($type) {
            case 'inventaris':
                $barangs = Barang::with(['kategori', 'lokasi'])->get();
                $title = 'Laporan Inventaris Barang';
                $view = 'laporan.pdf_inventaris';
                $data = compact('barangs', 'title');
                break;
            case 'transaksi':
                $transaksis = Transaksi::with(['barang'])->orderBy('created_at', 'desc')->get();
                $title = 'Laporan Transaksi';
                $view = 'laporan.pdf_transaksi';
                $data = compact('transaksis', 'title');
                break;
            case 'kategori':
                if (!$id) return back()->with('error', 'ID kategori tidak ditemukan');
                $kategori = Kategori::find($id);
                if (!$kategori) return back()->with('error', 'Kategori tidak ditemukan');
                $barangs = Barang::where('kategori_id', $id)->with(['kategori', 'lokasi'])->get();
                $title = 'Laporan Barang per Kategori: ' . $kategori->nama_kategori;
                $view = 'laporan.pdf_inventaris';
                $data = compact('barangs', 'title');
                break;
            case 'lokasi':
                if (!$id) return back()->with('error', 'ID lokasi tidak ditemukan');
                $lokasi = Lokasi::find($id);
                if (!$lokasi) return back()->with('error', 'Lokasi tidak ditemukan');
                $barangs = Barang::where('lokasi_id', $id)->with(['kategori', 'lokasi'])->get();
                $title = 'Laporan Barang per Lokasi: ' . $lokasi->nama_lokasi;
                $view = 'laporan.pdf_inventaris';
                $data = compact('barangs', 'title');
                break;
            case 'all':
            default:
                $barangs = Barang::with(['kategori', 'lokasi'])->get();
                $title = 'Laporan Semua Data (Inventaris)';
                $view = 'laporan.pdf_inventaris';
                $data = compact('barangs', 'title');
                break;
        }
        
        $html = view($view, $data)->render();
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $pdfContent = $dompdf->output();
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $title . '_' . date('Y-m-d') . '.pdf"'
        ]);
    }
}