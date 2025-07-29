<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajuanMagangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengajuan_magang')->insert([
            [
                'nama' => 'Rizky Maulana',
                'email' => 'rizky@example.com',
                'no_telp' => '081234567890',
                'instansi' => 'Universitas Indonesia',
                'jurusan' => 'Teknik Informatika',
                'status' => 'pending',
                'file_surat' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'no_telp' => '082134567891',
                'instansi' => 'Universitas Gadjah Mada',
                'jurusan' => 'Sistem Informasi',
                'status' => 'diterima',
                'file_surat' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
