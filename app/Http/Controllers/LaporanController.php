<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Kategori;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $transaksi = Transaksi::where('user_id', auth()->id())
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
            ->with('kategori')
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPemasukan = $transaksi->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transaksi->where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Kelompokkan per kategori
        $perKategori = $transaksi->groupBy('kategori_id')->map(function($items) {
            return [
                'kategori' => $items->first()->kategori->nama_kategori ?? 'Tanpa Kategori',
                'pemasukan' => $items->where('jenis', 'pemasukan')->sum('jumlah'),
                'pengeluaran' => $items->where('jenis', 'pengeluaran')->sum('jumlah'),
            ];
        });

        return view('laporan.index', compact('transaksi', 'totalPemasukan', 'totalPengeluaran', 'saldo', 'perKategori', 'tanggalMulai', 'tanggalAkhir'));
    }

    public function exportPdf(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $transaksi = Transaksi::where('user_id', auth()->id())
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
            ->with('kategori')
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPemasukan = $transaksi->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transaksi->where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $perKategori = $transaksi->groupBy('kategori_id')->map(function($items) {
            return [
                'kategori' => $items->first()->kategori->nama_kategori ?? 'Tanpa Kategori',
                'pemasukan' => $items->where('jenis', 'pemasukan')->sum('jumlah'),
                'pengeluaran' => $items->where('jenis', 'pengeluaran')->sum('jumlah'),
            ];
        });

        $user = auth()->user();

        $pdf = Pdf::loadView('laporan.pdf', compact('transaksi', 'totalPemasukan', 'totalPengeluaran', 'saldo', 'perKategori', 'tanggalMulai', 'tanggalAkhir', 'user'));
        
        return $pdf->download('laporan_keuangan_' . $tanggalMulai . '_' . $tanggalAkhir . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalAkhir = $request->input('tanggal_akhir');

        return Excel::download(new LaporanExport($tanggalMulai, $tanggalAkhir), 'laporan_keuangan_' . $tanggalMulai . '_' . $tanggalAkhir . '.xlsx');
    }
}