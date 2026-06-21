<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    protected $table = 'anggaran';
    protected $fillable = ['user_id', 'nama_anggaran', 'bulan', 'status'];
    protected $casts = ['bulan' => 'date'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function detailAnggaran(){
        return $this->hasMany(DetailAnggaran::class);
    }
}
