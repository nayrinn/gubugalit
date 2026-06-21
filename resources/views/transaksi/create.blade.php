@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Transaksi Baru</h3>
    </div>
    <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jenis">Jenis Transaksi <span class="text-danger">*</span></label>
                        <select class="form-control @error('jenis') is-invalid @enderror" 
                                id="jenis" name="jenis" required onchange="filterKategori()">
                            <option value="">Pilih Jenis</option>
                            <option value="pemasukan" {{ old('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ old('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                        @error('jenis')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                               id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select class="form-control @error('kategori_id') is-invalid @enderror" 
                                id="kategori_id" name="kategori_id">
                            <option value="">Pilih Kategori (opsional)</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->id }}" 
                                        data-jenis="{{ $k->jenis }}"
                                        {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jumlah">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                               id="jumlah" name="jumlah" value="{{ old('jumlah') }}" 
                               min="0" step="0.01" required placeholder="0">
                        @error('jumlah')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                          id="keterangan" name="keterangan" rows="3" 
                          placeholder="Keterangan transaksi (opsional)">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="bukti">Upload Bukti Transaksi</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input @error('bukti') is-invalid @enderror" 
                           id="bukti" name="bukti" accept="image/*,.pdf">
                    <label class="custom-file-label" for="bukti">Pilih file...</label>
                </div>
                <small class="form-text text-muted">Format: JPG, JPEG, PNG, PDF. Maksimal 2MB.</small>
                @error('bukti')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function formatRibuan(angka) {
    if (!angka) return '';
    angka = angka.replace(/^0+/, '') || '0';
    return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function filterKategori() {
    var jenis = document.getElementById('jenis').value;
    var kategoriSelect = document.getElementById('kategori_id');
    var options = kategoriSelect.getElementsByTagName('option');
    
    for (var i = 0; i < options.length; i++) {
        if (options[i].value === '') {
            options[i].style.display = 'block';
            continue;
        }
        
        var optionJenis = options[i].getAttribute('data-jenis');
        if (jenis === '' || optionJenis === jenis) {
            options[i].style.display = 'block';
        } else {
            options[i].style.display = 'none';
        }
    }
    
    var selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
    if (selectedOption && selectedOption.style.display === 'none') {
        kategoriSelect.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // format input jumlah
    const jumlahInput = document.getElementById('jumlah');
    if (jumlahInput) {
        jumlahInput.type = 'text';
        jumlahInput.setAttribute('inputmode', 'numeric');
        
        if (jumlahInput.value && jumlahInput.value != '0') {
            jumlahInput.value = formatRibuan(jumlahInput.value);
        }
        
        jumlahInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = formatRibuan(value);
        });
        
        jumlahInput.closest('form').addEventListener('submit', function() {
            jumlahInput.value = jumlahInput.value.replace(/\./g, '');
        });
    }
    
    // custom file input label
    const fileInput = document.querySelector('.custom-file-input');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file...';
            var label = e.target.nextElementSibling;
            label.textContent = fileName;
        });
    }
    
    // filter kategori saat page load
    filterKategori();
});
</script>
@endpush