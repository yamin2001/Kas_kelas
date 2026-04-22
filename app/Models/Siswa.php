<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $fillable = ['nama','user_id',];
   
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'siswa_id');
    }
}