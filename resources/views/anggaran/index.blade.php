@extends('layouts.app')

@section('title', 'Anggaran')

@section('breadcrumb')
    <li class="breadcrumb-item active">Anggaran</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Anggaran</h3>
        <div class="card-tools">
            <a href="{{ route('anggaran.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Buat Anggaran Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Anggaran</th>
                    <th>Bulan</th>
                    <th>Status</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggaran as $a)
                <tr>
                    <td>{{ $a->nama_anggaran }}</td>
                    <td>{{ $a->bulan->format('F Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $a->status == 'aktif' ? 'success' : 'secondary' }}">
                            {{ ucfirst($a->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('anggaran.show', $a->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <a href="{{ route('anggaran.edit', $a->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('anggaran.destroy', $a->id) }}" method="POST" class="d-inline">
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
                    <td colspan="4" class="text-center">Belum ada anggaran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection