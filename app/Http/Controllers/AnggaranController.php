<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggaran;
use App\Models\DetailAnggaran;
use App\Models\Kategori;
use Carbon\Carbon;

class AnggaranController extends Controller
{
    public function index()
    {
        $anggaran = Anggaran::where('user_id', auth()->id())
            ->orderBy('bulan', 'desc')
            ->get();
        return view('anggaran.index', compact('anggaran'));
    }

    public function create()
    {
        $kategoriPemasukan = Kategori::where('user_id', auth()->id())
            ->where('jenis', 'pemasukan')
            ->get();
        $kategoriPengeluaran = Kategori::where('user_id', auth()->id())
            ->where('jenis', 'pengeluaran')
            ->get();
            
        return view('anggaran.create', compact('kategoriPemasukan', 'kategoriPengeluaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_anggaran' => 'required|string|max:255',
            'bulan' => 'required|date',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $anggaran = Anggaran::create([
            'user_id' => auth()->id(),
            'nama_anggaran' => $request->nama_anggaran,
            'bulan' => $request->bulan,
            'status' => $request->status
        ]);

        if ($request->has('kategori_pemasukan')) {
            foreach ($request->kategori_pemasukan as $kategoriId => $jumlah) {
                if ($jumlah > 0) {
                    DetailAnggaran::create([
                        'anggaran_id' => $anggaran->id,
                        'kategori_id' => $kategoriId,
                        'jenis' => 'pemasukan',
                        'jumlah' => $jumlah,
                        'keterangan' => $request->keterangan_pemasukan[$kategoriId] ?? null
                    ]);
                }
            }
        }

        if ($request->has('kategori_pengeluaran')) {
            foreach ($request->kategori_pengeluaran as $kategoriId => $jumlah) {
                if ($jumlah > 0) {
                    DetailAnggaran::create([
                        'anggaran_id' => $anggaran->id,
                        'kategori_id' => $kategoriId,
                        'jenis' => 'pengeluaran',
                        'jumlah' => $jumlah,
                        'keterangan' => $request->keterangan_pengeluaran[$kategoriId] ?? null
                    ]);
                }
            }
        }

        if ($request->total_pemasukan > 0) {
            DetailAnggaran::create([
                'anggaran_id' => $anggaran->id,
                'kategori_id' => null,
                'jenis' => 'pemasukan',
                'jumlah' => $request->total_pemasukan,
                'keterangan' => 'Total Anggaran Pemasukan'
            ]);
        }

        if ($request->total_pengeluaran > 0) {
            DetailAnggaran::create([
                'anggaran_id' => $anggaran->id,
                'kategori_id' => null,
                'jenis' => 'pengeluaran',
                'jumlah' => $request->total_pengeluaran,
                'keterangan' => 'Total Anggaran Pengeluaran'
            ]);
        }

        return redirect()->route('anggaran.index')->with('success', 'Anggaran berhasil dibuat');
    }

    public function show(Anggaran $anggaran)
    {
        if ($anggaran->user_id !== auth()->id()) {
            abort(403);
        }

        $anggaran->load('detailAnggaran.kategori');
        
        $bulan = Carbon::parse($anggaran->bulan);
        $realisasi = [];
        
        foreach ($anggaran->detailAnggaran as $detail) {
            // Jika ada kategori_id, hitung realisasi per kategori
            if ($detail->kategori_id) {
                $jumlahRealisasi = \App\Models\Transaksi::where('user_id', auth()->id())
                    ->where('jenis', $detail->jenis)
                    ->where('kategori_id', $detail->kategori_id)
                    ->whereYear('tanggal', $bulan->year)
                    ->whereMonth('tanggal', $bulan->month)
                    ->sum('jumlah');
            } else {
                // Jika tidak ada kategori_id (total), hitung semua transaksi dengan jenis yang sama
                $jumlahRealisasi = \App\Models\Transaksi::where('user_id', auth()->id())
                    ->where('jenis', $detail->jenis)
                    ->whereYear('tanggal', $bulan->year)
                    ->whereMonth('tanggal', $bulan->month)
                    ->sum('jumlah');
            }
            
            $realisasi[$detail->id] = $jumlahRealisasi;
        }
        
        return view('anggaran.show', compact('anggaran', 'realisasi'));
    }

    public function edit(Anggaran $anggaran)
    {
        if ($anggaran->user_id !== auth()->id()) {
            abort(403);
        }

        $kategoriPemasukan = Kategori::where('user_id', auth()->id())
            ->where('jenis', 'pemasukan')
            ->get();
        $kategoriPengeluaran = Kategori::where('user_id', auth()->id())
            ->where('jenis', 'pengeluaran')
            ->get();
            
        $anggaran->load('detailAnggaran');
        
        return view('anggaran.edit', compact('anggaran', 'kategoriPemasukan', 'kategoriPengeluaran'));
    }

    public function update(Request $request, Anggaran $anggaran)
    {
        if ($anggaran->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'nama_anggaran' => 'required|string|max:255',
            'bulan' => 'required|date',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $anggaran->update([
            'nama_anggaran' => $request->nama_anggaran,
            'bulan' => $request->bulan,
            'status' => $request->status
        ]);

        $anggaran->detailAnggaran()->delete();

        if ($request->has('kategori_pemasukan')) {
            foreach ($request->kategori_pemasukan as $kategoriId => $jumlah) {
                if ($jumlah > 0) {
                    DetailAnggaran::create([
                        'anggaran_id' => $anggaran->id,
                        'kategori_id' => $kategoriId,
                        'jenis' => 'pemasukan',
                        'jumlah' => $jumlah,
                        'keterangan' => $request->keterangan_pemasukan[$kategoriId] ?? null
                    ]);
                }
            }
        }

        if ($request->has('kategori_pengeluaran')) {
            foreach ($request->kategori_pengeluaran as $kategoriId => $jumlah) {
                if ($jumlah > 0) {
                    DetailAnggaran::create([
                        'anggaran_id' => $anggaran->id,
                        'kategori_id' => $kategoriId,
                        'jenis' => 'pengeluaran',
                        'jumlah' => $jumlah,
                        'keterangan' => $request->keterangan_pengeluaran[$kategoriId] ?? null
                    ]);
                }
            }
        }

        if ($request->total_pemasukan > 0) {
            DetailAnggaran::create([
                'anggaran_id' => $anggaran->id,
                'kategori_id' => null,
                'jenis' => 'pemasukan',
                'jumlah' => $request->total_pemasukan,
                'keterangan' => 'Total Anggaran Pemasukan'
            ]);
        }

        if ($request->total_pengeluaran > 0) {
            DetailAnggaran::create([
                'anggaran_id' => $anggaran->id,
                'kategori_id' => null,
                'jenis' => 'pengeluaran',
                'jumlah' => $request->total_pengeluaran,
                'keterangan' => 'Total Anggaran Pengeluaran'
            ]);
        }

        return redirect()->route('anggaran.index')->with('success', 'Anggaran berhasil diupdate');
    }

    public function destroy(Anggaran $anggaran)
    {
        if ($anggaran->user_id !== auth()->id()) {
            abort(403);
        }

        $anggaran->delete();
        return redirect()->route('anggaran.index')->with('success', 'Anggaran berhasil dihapus');
    }
}