<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HasilMagang;
use App\Models\Penerimaan;

class HasilMagangSeeder extends Seeder
{
    public function run(): void
    {
        // Get some approved penerimaan records
        $approvedPenerimaan = Penerimaan::where('status', 'approved')->take(2)->get();

        if ($approvedPenerimaan->count() > 0) {
            foreach ($approvedPenerimaan as $penerimaan) {
                HasilMagang::create([
                    'penerimaan_id' => $penerimaan->id,
                    'laporan_hasil_magang' => 'sample_laporan.pdf', // Sample file path
                    'status' => 'pending',
                    'tanggal_selesai' => now(),
                    'catatan' => 'Sample laporan hasil magang untuk testing'
                ]);
            }
        }
    }
}
