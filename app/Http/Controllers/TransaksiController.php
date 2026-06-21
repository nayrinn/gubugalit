<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::where('user_id', auth()->id())
            ->with('kategori');

        // Filter
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        $transaksi = $query->orderBy('tanggal', 'desc')->paginate(20);
        $kategori = Kategori::where('user_id', auth()->id())->get();

        return view('transaksi.index', compact('transaksi', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::where('user_id', auth()->id())->get();
        return view('transaksi.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'kategori_id' => 'nullable|exists:kategori,id',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $data = [
            'user_id' => auth()->id(),
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'kategori_id' => $request->kategori_id,
            'keterangan' => $request->keterangan
        ];

        // Upload bukti
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bukti_transaksi', $filename, 'public');
            $data['bukti'] = $path;
        }

        Transaksi::create($data);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function show(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        $kategori = Kategori::where('user_id', auth()->id())->get();
        return view('transaksi.edit', compact('transaksi', 'kategori'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        if ($transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'kategori_id' => 'nullable|exists:kategori,id',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $data = [
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'kategori_id' => $request->kategori_id,
            'keterangan' => $request->keterangan
        ];

        // Upload bukti baru
        if ($request->hasFile('bukti')) {
            // Hapus bukti lama
            if ($transaksi->bukti) {
                Storage::disk('public')->delete($transaksi->bukti);
            }

            $file = $request->file('bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bukti_transaksi', $filename, 'public');
            $data['bukti'] = $path;
        }

        $transaksi->update($data);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diupdate');
    }

    public function destroy(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        // Hapus bukti jika ada
        if ($transaksi->bukti) {
            Storage::disk('public')->delete($transaksi->bukti);
        }

        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }
}