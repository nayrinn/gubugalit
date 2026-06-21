<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'nama_umkm', 'alamat_umkm', 'telepon'
    ];

    public function kategori() {
        return $this->hasMany(Kategori::class);
    }

    public function anggaran(){
        return $this->hasMany(Anggaran::class);
    }

    public function transaksi(){
        return $this->hasMany(Transaksi::class);
    }
}
