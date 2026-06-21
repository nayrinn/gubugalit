<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailAnggaran extends Model
{
    protected $table = 'detail_anggaran';
    protected $fillable = ['anggaran_id', 'kategori_id', 'jenis', 'jumlah', 'keterangan'];

    public function anggaran(){
        return $this->belongsTo(Anggaran::class);
    }

    public function kategori(){
        return $this->belongsTo(Kategori::class);
    }
}
