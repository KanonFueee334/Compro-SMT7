<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penerimaan;
use App\Models\PengajuanMagang;
use App\Models\Lokasi;

class PenerimaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some accepted applications and locations
        $acceptedApplications = PengajuanMagang::where('status', 'diterima')->take(3)->get();
        $locations = Lokasi::take(2)->get();
        
        if ($acceptedApplications->count() > 0 && $locations->count() > 0) {
            foreach ($acceptedApplications as $index => $application) {
                Penerimaan::create([
                    'pengajuan_id' => $application->id,
                    'peserta_magang' => [
                        [
                            'nama' => $application->nama_pemohon,
                            'telepon' => $application->no_hp
                        ],
                        [
                            'nama' => 'Peserta ' . ($index + 2),
                            'telepon' => '08123456789' . ($index + 1)
                        ]
                    ],
                    'instansi_sekolah_universitas' => $application->asal_instansi ?? 'Universitas Sample',
                    'jurusan' => $application->jurusan ?? 'Teknik Informatika',
                    'lokasi_id' => $locations[$index % $locations->count()]->id,
                    'mulai_magang' => now()->addDays(30),
                    'selesai_magang' => now()->addDays(90),
                    'status' => 'pending',
                    'catatan' => 'Sample catatan untuk testing'
                ]);
            }
        }
    }
}
