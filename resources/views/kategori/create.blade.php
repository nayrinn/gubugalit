@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Kategori Baru</h3>
    </div>
    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" 
                       id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}" required>
                @error('nama_kategori')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="jenis">Jenis Kategori</label>
                <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis" required>
                    <option value="">Pilih Jenis</option>
                    <option value="pemasukan" {{ old('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ old('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
                @error('jenis')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection