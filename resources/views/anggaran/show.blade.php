@extends('layouts.app')

@section('title', 'Detail Anggaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('anggaran.index') }}">Anggaran</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $anggaran->nama_anggaran }}</h3>
        <div class="card-tools">
            <span class="badge badge-{{ $anggaran->status == 'aktif' ? 'success' : 'secondary' }}">
                {{ ucfirst($anggaran->status) }}
            </span>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Bulan:</strong> {{ $anggaran->bulan->format('F Y') }}
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('anggaran.edit', $anggaran->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>

        <div class="row">
            <!-- pemasukan -->
            <div class="col-md-6">
                <h4 class="text-success">Anggaran Pemasukan</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Anggaran</th>
                            <th>Realisasi</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAnggaranPemasukan = 0;
                            $totalRealisasiPemasukan = 0;
                        @endphp
                        @foreach($anggaran->detailAnggaran->where('jenis', 'pemasukan') as $detail)
                        @php
                            $totalAnggaranPemasukan += $detail->jumlah;
                            $totalRealisasiPemasukan += $realisasi[$detail->id];
                            $persentase = $detail->jumlah > 0 ? ($realisasi[$detail->id] / $detail->jumlah * 100) : 0;
                        @endphp
                        <tr>
                            <td>
                                {{ $detail->kategori->nama_kategori ?? $detail->keterangan }}
                                @if($detail->keterangan && $detail->kategori)
                                    <br><small class="text-muted">{{ $detail->keterangan }}</small>
                                @endif
                            </td>
                            <td>Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($realisasi[$detail->id], 0, ',', '.') }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: {{ min($persentase, 100) }}%">
                                        {{ number_format($persentase, 1) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="font-weight-bold">
                            <td>TOTAL</td>
                            <td>Rp {{ number_format($totalAnggaranPemasukan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($totalRealisasiPemasukan, 0, ',', '.') }}</td>
                            <td>
                                {{ $totalAnggaranPemasukan > 0 ? number_format($totalRealisasiPemasukan / $totalAnggaranPemasukan * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- pengeluaran -->
            <div class="col-md-6">
                <h4 class="text-danger">Anggaran Pengeluaran</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Anggaran</th>
                            <th>Realisasi</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAnggaranPengeluaran = 0;
                            $totalRealisasiPengeluaran = 0;
                        @endphp
                        @foreach($anggaran->detailAnggaran->where('jenis', 'pengeluaran') as $detail)
                        @php
                            $totalAnggaranPengeluaran += $detail->jumlah;
                            $totalRealisasiPengeluaran += $realisasi[$detail->id];
                            $persentase = $detail->jumlah > 0 ? ($realisasi[$detail->id] / $detail->jumlah * 100) : 0;
                        @endphp
                        <tr>
                            <td>
                                {{ $detail->kategori->nama_kategori ?? $detail->keterangan }}
                                @if($detail->keterangan && $detail->kategori)
                                    <br><small class="text-muted">{{ $detail->keterangan }}</small>
                                @endif
                            </td>
                            <td>Rp {{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($realisasi[$detail->id], 0, ',', '.') }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar {{ $persentase > 100 ? 'bg-danger' : 'bg-warning' }}" 
                                         style="width: {{ min($persentase, 100) }}%">
                                        {{ number_format($persentase, 1) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="font-weight-bold">
                            <td>TOTAL</td>
                            <td>Rp {{ number_format($totalAnggaranPengeluaran, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($totalRealisasiPengeluaran, 0, ',', '.') }}</td>
                            <td>
                                {{ $totalAnggaranPengeluaran > 0 ? number_format($totalRealisasiPengeluaran / $totalAnggaranPengeluaran * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection