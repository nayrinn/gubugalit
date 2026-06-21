@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Transaksi</h3>
        <div class="card-tools">
            <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Jenis</th>
                        <td>
                            <span class="badge badge-{{ $transaksi->jenis == 'pemasukan' ? 'success' : 'danger' }} badge-lg">
                                {{ ucfirst($transaksi->jenis) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $transaksi->tanggal->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $transaksi->kategori->nama_kategori ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td class="h4">
                            <strong>Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Keterangan</th>
                        <td>{{ $transaksi->keterangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Bukti Transaksi</th>
                        <td>
                            @if($transaksi->bukti)
                                @php
                                    $extension = pathinfo($transaksi->bukti, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                    <a href="{{ asset('storage/'.$transaksi->bukti) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$transaksi->bukti) }}" 
                                             class="img-thumbnail" style="max-width: 200px;">
                                    </a>
                                @else
                                    <a href="{{ asset('storage/'.$transaksi->bukti) }}" 
                                       target="_blank" class="btn btn-info">
                                        <i class="fas fa-file-pdf"></i> Lihat Bukti
                                    </a>
                                @endif
                            @else
                                <span class="text-muted">Tidak ada bukti</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $transaksi->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection