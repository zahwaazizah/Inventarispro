<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['barang']);
        
        if ($request->has('dari_tanggal') && $request->dari_tanggal) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari_tanggal);
        }
        if ($request->has('sampai_tanggal') && $request->sampai_tanggal) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai_tanggal);
        }
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('barang', function($q) use ($search) {
                $q->where('kode_inventaris', 'LIKE', "%{$search}%")
                  ->orWhere('nama_barang', 'LIKE', "%{$search}%");
            });
        }
        if ($request->has('status') && $request->status) {
            if ($request->status == 'dikembalikan') {
                $query->where('status', 'dikembalikan');
            } elseif ($request->status == 'dipinjam') {
                $query->where('status', 'dipinjam');
            } elseif ($request->status == 'terlambat') {
                $query->where('status', 'dipinjam')
                      ->whereDate('tanggal_kembali', '<', now());
            }
        }
        
        $riwayats = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('riwayat.index', compact('riwayats'));
    }
    
    public function filter(Request $request)
    {
        return $this->index($request);
    }
    
    /**
     * Export ke Excel (HTML sederhana)
     */
    public function exportExcel(Request $request)
    {
        $riwayats = Transaksi::with(['barang'])->orderBy('created_at', 'desc')->get();
        $title = 'Laporan Riwayat Transaksi';
        
        $html = view('riwayat.excel', compact('riwayats', 'title'))->render();
        
        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $title . '_' . date('Y-m-d') . '.xls"'
        ]);
    }
    
    /**
     * Export ke PDF (langsung download)
     */
    public function exportPdf(Request $request)
    {
        $riwayats = Transaksi::with(['barang'])->orderBy('created_at', 'desc')->get();
        $title = 'Laporan Riwayat Transaksi';
        
        $html = view('riwayat.pdf', compact('riwayats', 'title'))->render();
        
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