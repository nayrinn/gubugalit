@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Kategori</h3>
    </div>
    <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" 
                       id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                @error('nama_kategori')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="jenis">Jenis Kategori</label>
                <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis" required>
                    <option value="">Pilih Jenis</option>
                    <option value="pemasukan" {{ old('jenis', $kategori->jenis) == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ old('jenis', $kategori->jenis) == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
                @error('jenis')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection