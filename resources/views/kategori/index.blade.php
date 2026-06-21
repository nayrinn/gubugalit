@extends('layouts.app')

@section('title', 'Kategori')

@section('breadcrumb')
    <li class="breadcrumb-item active">Kategori</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kategori</h3>
        <div class="card-tools">
            <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Kategori
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- pemasukan -->
            <div class="col-md-6">
                <h5 class="text-success">Kategori Pemasukan</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori->where('jenis', 'pemasukan') as $k)
                        <tr>
                            <td>{{ $k->nama_kategori }}</td>
                            <td>
                                <a href="{{ route('kategori.edit', $k->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">Belum ada kategori pemasukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- pengeluaran -->
            <div class="col-md-6">
                <h5 class="text-danger">Kategori Pengeluaran</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori->where('jenis', 'pengeluaran') as $k)
                        <tr>
                            <td>{{ $k->nama_kategori }}</td>
                            <td>
                                <a href="{{ route('kategori.edit', $k->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">Belum ada kategori pengeluaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection