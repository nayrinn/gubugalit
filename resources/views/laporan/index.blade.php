@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan Keuangan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Keuangan</h3>
    </div>
    <div class="card-body">
        <!-- filter waktu -->
        <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" 
                           value="{{ $tanggalMulai }}" required>
                </div>
                <div class="col-md-3">
                    <label for="tanggal_akhir">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control" 
                           value="{{ $tanggalAkhir }}" required>
                </div>
                <div class="col-md-6">
                    <label>&nbsp;</label><br>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                    <a href="{{ route('laporan.export.pdf', ['tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir]) }}" 
                       class="btn btn-danger" target="_blank">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('laporan.export.excel', ['tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir]) }}" 
                       class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </form>

        <hr>

        <!-- ringkasan -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pemasukan</span>
                        <span class="info-box-number">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pengeluaran</span>
                        <span class="info-box-number">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Saldo</span>
                        <span class="info-box-number">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- laporan per kategori -->
        <h5>Ringkasan Per Kategori</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Kategori</th>
                        <th class="text-right">Pemasukan</th>
                        <th class="text-right">Pengeluaran</th>
                        <th class="text-right">Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perKategori as $item)
                    <tr>
                        <td>{{ $item['kategori'] }}</td>
                        <td class="text-right text-success">
                            Rp {{ number_format($item['pemasukan'], 0, ',', '.') }}
                        </td>
                        <td class="text-right text-danger">
                            Rp {{ number_format($item['pengeluaran'], 0, ',', '.') }}
                        </td>
                        <td class="text-right {{ ($item['pemasukan'] - $item['pengeluaran']) >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($item['pemasukan'] - $item['pengeluaran'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data kategori</td>
                    </tr>
                    @endforelse
                    <tr class="font-weight-bold">
                        <td>TOTAL</td>
                        <td class="text-right text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                        <td class="text-right text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                        <td class="text-right {{ $saldo >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($saldo, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- detail transaksi -->
        <h5>Detail Transaksi</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th class="text-right">Jumlah</th>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada transaksi pada periode ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection