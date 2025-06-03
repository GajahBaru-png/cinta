<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        Siswa::insert([
            [
                'nama' => 'AKBAR ADHA KUSUMAWARDHANA',
                'nis' => '20394',
                'gender' => 'L',
                'alamat' => 'Jl. Kaliurang Km.7',
                'kontak' => '081234567890',
                'email' => '20394@sija.com',
                
            ],
            [
                'nama' => 'MARCELLINUS CHRISTO PRADIPTA',
                'nis' => '20422',
                'gender' => 'L',
                'alamat' => 'Jl. Kebon Agung',
                'kontak' => '082134567891',
                'email' => '20422@sija.com',

            ],
        ]);
    }
}