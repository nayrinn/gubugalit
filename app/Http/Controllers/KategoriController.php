<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::where('user_id', auth()->id())->get();
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'jenis' => 'required|in:pemasukan,pengeluaran'
        ]);

        Kategori::create([
            'user_id' => auth()->id(),
            'nama_kategori' => $request->nama_kategori,
            'jenis' => $request->jenis
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Kategori $kategori)
    {
        // validasi user login
        if ($kategori->user_id !== auth()->id()) {
            abort(403);
        }

        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        if ($kategori->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'jenis' => 'required|in:pemasukan,pengeluaran'
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'jenis' => $request->jenis
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->user_id !==auth()->id()) {
            abort(403);
        }

        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}