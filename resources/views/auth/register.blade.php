@extends('layouts.guest')

@section('content')
<div class="register-box">
    <div class="register-logo">
        <b>UMKM</b> Management
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Daftar untuk memulai</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('nama_umkm') is-invalid @enderror" 
                           name="nama_umkm" value="{{ old('nama_umkm') }}" placeholder="Nama UMKM" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-store"></span>
                        </div>
                    </div>
                    @error('nama_umkm')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <textarea class="form-control @error('alamat_umkm') is-invalid @enderror" 
                              name="alamat_umkm" placeholder="Alamat UMKM" rows="2">{{ old('alamat_umkm') }}</textarea>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-map-marker-alt"></span>
                        </div>
                    </div>
                    @error('alamat_umkm')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                           name="telepon" value="{{ old('telepon') }}" placeholder="Telepon">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-phone"></span>
                        </div>
                    </div>
                    @error('telepon')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control" 
                           name="password_confirmation" placeholder="Konfirmasi Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                    </div>
                </div>
            </form>

            <p class="mb-0 mt-3">
                <a href="{{ route('login') }}" class="text-center">Sudah punya akun? Masuk</a>
            </p>
        </div>
    </div>
</div>
@endsection