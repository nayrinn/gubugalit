<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Anggaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $bulanIni = Carbon::now();
        
        // statistik bulan ini
        $totalPemasukan = Transaksi::where('user_id', $user->id)
            ->where('jenis', 'pemasukan')
            ->whereYear('tanggal', $bulanIni->year)
            ->whereMonth('tanggal', $bulanIni->month)
            ->sum('jumlah');
            
        $totalPengeluaran = Transaksi::where('user_id', $user->id)
            ->where('jenis', 'pengeluaran')
            ->whereYear('tanggal', $bulanIni->year)
            ->whereMonth('tanggal', $bulanIni->month)
            ->sum('jumlah');
            
        $saldo = $totalPemasukan - $totalPengeluaran;
        
        // transaksi terbaru
        $transaksiTerbaru = Transaksi::where('user_id', $user->id)
            ->with('kategori')
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // anggaran aktif bulan ini
        $anggaranAktif = Anggaran::where('user_id', $user->id)
            ->where('status', 'aktif')
            ->whereYear('bulan', $bulanIni->year)
            ->whereMonth('bulan', $bulanIni->month)
            ->with('detailAnggaran')
            ->first();
        
        return view('dashboard', compact('totalPemasukan', 'totalPengeluaran', 'saldo', 'transaksiTerbaru', 'anggaranAktif'));
    }
}