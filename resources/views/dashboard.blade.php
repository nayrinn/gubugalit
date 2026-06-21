@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- total pemasukan -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                <p>Total Pemasukan Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-arrow-up"></i>
            </div>
        </div>
    </div>

    <!-- total pengeluaran -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                <p>Total Pengeluaran Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-arrow-down"></i>
            </div>
        </div>
    </div>

    <!-- saldo -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                <p>Saldo Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- transaksi terbaru -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Transaksi Terbaru</h3>
                <div class="card-tools">
                    <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $t)
                        <tr>
                            <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $t->jenis == 'pemasukan' ? 'success' : 'danger' }}">
                                    {{ ucfirst($t->jenis) }}
                                </span>
                            </td>
                            <td>{{ $t->kategori->nama_kategori ?? '-' }}</td>
                            <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- anggaran aktif -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Anggaran Bulan Ini</h3>
            </div>
            <div class="card-body">
                @if($anggaranAktif)
                    <h5>{{ $anggaranAktif->nama_anggaran }}</h5>
                    <p class="text-muted">{{ $anggaranAktif->bulan->format('F Y') }}</p>

                    @php
                        $totalAnggaranPemasukan = $anggaranAktif->detailAnggaran->where('jenis', 'pemasukan')->sum('jumlah');
                        $totalAnggaranPengeluaran = $anggaranAktif->detailAnggaran->where('jenis', 'pengeluaran')->sum('jumlah');
                    @endphp

                    <div class="mb-2">
                        <small class="text-muted">Target Pemasukan</small>
                        <h6 class="text-success mb-0">RP {{ number_format($totalAnggaranPemasukan, 0, ',', '.') }}</h6>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Target Pengeluaran</small>
                        <h6 class="text-success mb-0">RP {{ number_format($totalAnggaranPengeluaran, 0, ',', '.') }}</h6>
                    </div>

                    <a href="{{ route('anggaran.show', $anggaranAktif->id) }}" class="btn btn-sm btn-info btn-block">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                @else
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle"></i> Belum ada anggaran aktif untuk bulan ini
                    </div>
                    <a href="{{ route('anggaran.create') }}" class="btn btn-sm btn-primary btn-block">
                        <i class="fas fa-plus"></i> Buat Anggaran
                    </a>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('transaksi.create') }}" class="btn btn-success btn-block mb-2">
                    <i class="fas fa-plus"></i> Tambah Transaksi
                </a>
                <a href="{{ route('laporan.index') }}" class="btn btn-info btn-block">
                    <i class="fas fa-file-alt"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection