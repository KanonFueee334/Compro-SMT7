<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengajuanPenelitianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengajuan_penelitian')->insert([
            [
                'nama' => 'Budi Santoso',
                'instansi' => 'Universitas Negeri Surabaya',
                'jurusan' => 'Teknik Informatika',
                'judul_penelitian' => 'Analisis Sistem Informasi Pemerintahan',
                'metode' => 'Kuesioner',
                'surat_izin' => 'penelitian/surat_izin/dummy1.pdf',
                'proposal' => 'penelitian/proposal/dummy1.pdf',
                'daftar_pertanyaan' => 'penelitian/daftar_pertanyaan/dummy1.pdf',
                'ktp' => 'penelitian/ktp/dummy1.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Aminah',
                'instansi' => 'Institut Teknologi Sepuluh Nopember',
                'jurusan' => 'Sistem Informasi',
                'judul_penelitian' => 'Studi Penggunaan Aplikasi Mobile di Masyarakat',
                'metode' => 'Wawancara',
                'surat_izin' => 'penelitian/surat_izin/dummy2.pdf',
                'proposal' => 'penelitian/proposal/dummy2.pdf',
                'daftar_pertanyaan' => 'penelitian/daftar_pertanyaan/dummy2.pdf',
                'ktp' => 'penelitian/ktp/dummy2.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 