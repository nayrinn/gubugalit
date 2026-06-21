<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Test User',
            'nama_umkm' => 'UMKM Test',
            'alamat_umkm' => 'Jl. Test 123',
            'telepon' => '081234567890',
            'email' => 'tes@umkm.com',
            'password' => '12345678'
        ]);

        // kategori pemasukan
        Kategori::create(['user_id' => $user->id, 'nama_kategori' => 'Penjualan Produk', 'jenis' => 'pemasukan']);
        Kategori::create(['user_id' => $user->id, 'nama_kategori' => 'Jasa', 'jenis' => 'pemasukan']);

        //  kategori pengeluaran
        Kategori::create(['user_id' => $user->id, 'nama_kategori' => 'Bahan Baku', 'jenis' => 'pengeluaran']);
        Kategori::create(['user_id' => $user->id, 'nama_kategori' => 'Operasional', 'jenis' => 'pengeluaran']);
        Kategori::create(['user_id' => $user->id, 'nama_kategori' => 'Gaji Karyawan', 'jenis' => 'pengeluaran']);
    }
}
