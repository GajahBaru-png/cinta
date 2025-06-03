<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;
    protected $table = 'siswa';

    protected $fillable = ['nama', 'nis', 'gender', 'alamat', 'kontak', 'email', 'foto', 'status_pkl'];
    
    public function pkl()
    {
        return $this->hasOne(PKL::class); //karena satu siswa hanya bisa 1 laporan PKL
    }
}

