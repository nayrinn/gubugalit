@extends('layouts.app')

@section('title', 'Buat Anggaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('anggaran.index') }}">Anggaran</a></li>
    <li class="breadcrumb-item active">Buat</li>
@endsection

@section('content')
<form action="{{ route('anggaran.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informasi Anggaran</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_anggaran">Nama Anggaran</label>
                        <input type="text" class="form-control @error('nama_anggaran') is-invalid @enderror" 
                               id="nama_anggaran" name="nama_anggaran" value="{{ old('nama_anggaran') }}" required>
                        @error('nama_anggaran')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <input type="month" class="form-control @error('bulan') is-invalid @enderror" 
                               id="bulan" name="bulan" value="{{ old('bulan') }}" required>
                        @error('bulan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- pemasukan -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">Anggaran Pemasukan</h3>
                </div>
                <div class="card-body">
                    <h5>Per Kategori</h5>
                    @forelse($kategoriPemasukan as $k)
                    <div class="form-group">
                        <label>{{ $k->nama_kategori }}</label>
                        <input type="number" class="form-control" 
                               name="kategori_pemasukan[{{ $k->id }}]" 
                               value="{{ old('kategori_pemasukan.'.$k->id, 0) }}">
                        <input type="text" class="form-control mt-1" 
                               name="keterangan_pemasukan[{{ $k->id }}]" 
                               placeholder="Keterangan (opsional)" 
                               value="{{ old('keterangan_pemasukan.'.$k->id) }}">
                    </div>
                    @empty
                    <p class="text-muted">Belum ada kategori pemasukan. <a href="{{ route('kategori.create') }}">Tambah kategori</a></p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- pengeluaran -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger">
                    <h3 class="card-title">Anggaran Pengeluaran</h3>
                </div>
                <div class="card-body">
                    <h5>Per Kategori</h5>
                    @forelse($kategoriPengeluaran as $k)
                    <div class="form-group">
                        <label>{{ $k->nama_kategori }}</label>
                        <input type="number" class="form-control" 
                               name="kategori_pengeluaran[{{ $k->id }}]" 
                               value="{{ old('kategori_pengeluaran.'.$k->id, 0) }}">
                        <input type="text" class="form-control mt-1" 
                               name="keterangan_pengeluaran[{{ $k->id }}]" 
                               placeholder="Keterangan (opsional)" 
                               value="{{ old('keterangan_pengeluaran.'.$k->id) }}">
                    </div>
                    @empty
                    <p class="text-muted">Belum ada kategori pengeluaran. <a href="{{ route('kategori.create') }}">Tambah kategori</a></p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Anggaran</button>
            <a href="{{ route('anggaran.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const numberInputs = document.querySelectorAll('input[type="number"][name^="kategori_"]');
    
    numberInputs.forEach(input => {
        input.type = 'text';
        input.setAttribute('inputmode', 'numeric');
        
        if (input.value && input.value != '0') {
            input.value = formatRibuan(input.value);
        }
        
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = formatRibuan(value);
        });
        
        input.closest('form').addEventListener('submit', function() {
            input.value = input.value.replace(/\./g, '');
        });
    });
    
    function formatRibuan(angka) {
        if (!angka) return '';
        angka = angka.replace(/^0+/, '') || '0';
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
});
</script>
@endpush

@endsection