@extends('layouts.app')

@section('title', 'Transaksi')

@section('breadcrumb')
    <li class="breadcrumb-item active">Transaksi</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Transaksi</h3>
        <div class="card-tools">
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Transaksi
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- filter -->
        <form method="GET" action="{{ route('transaksi.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-2">
                    <select name="jenis" class="form-control">
                        <option value="">Semua Jenis</option>
                        <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="kategori_id" class="form-control">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal_mulai" class="form-control" 
                           value="{{ request('tanggal_mulai') }}" placeholder="Tanggal Mulai">
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal_akhir" class="form-control" 
                           value="{{ request('tanggal_akhir') }}" placeholder="Tanggal Akhir">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- tabel transaksi -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Bukti</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                    <tr>
                        <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $t->jenis == 'pemasukan' ? 'success' : 'danger' }}">
                                {{ ucfirst($t->jenis) }}
                            </span>
                        </td>
                        <td>{{ $t->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $t->keterangan ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($t->bukti)
                                <a href="{{ asset('storage/'.$t->bukti) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file"></i>
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('transaksi.show', $t->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('transaksi.edit', $t->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- pagination -->
        <div class="mt-3">
            {{ $transaksi->links() }}
        </div>
    </div>
</div>
@endsection