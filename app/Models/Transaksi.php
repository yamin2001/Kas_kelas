<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi'; 

    protected $fillable = [
        'siswa_id', 
        'nominal', 
        'jenis', 
        'status',
        'keterangan', 
        'tanggal', 
        'bukti_foto'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}