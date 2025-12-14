<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\PengajuanMagang;
use App\Models\Penerimaan;
use App\Models\HasilMagang;
use Illuminate\Support\Facades\Hash;

class HasilMagangAdminTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'name' => 'Admin',
            'role' => 'admin',
            'status' => 1,
        ]);
    }

    private function seedPenerimaan(): Penerimaan
    {
        $lokasi = Lokasi::create([
            'bidang' => 'Software Development',
            'tim' => 'Tim A',
            'quota' => 10,
            'alamat' => null,
        ]);

        $pengajuan = PengajuanMagang::create([
            'nama_pemohon' => 'Okky',
            'no_hp' => '081234567890',
            'asal_instansi' => 'Universitas Telkom Surabaya',
            'jurusan' => 'Informatika',
            'keahlian' => 'Laravel, PHP',
            'lokasi_id' => $lokasi->id,
            'mulai_magang' => now()->toDateString(),
            'selesai_magang' => now()->addMonths(3)->toDateString(),
            'email' => 'okky@example.com',
            'status' => 'diterima',
        ]);

        return Penerimaan::create([
            'pengajuan_id' => $pengajuan->id,
            'peserta_magang' => [
                ['nama' => 'Okky', 'telepon' => '081234567890'],
            ],
            'instansi_sekolah_universitas' => 'Universitas Telkom Surabaya',
            'jurusan' => 'Informatika',
            'lokasi_id' => $lokasi->id,
            'mulai_magang' => now()->toDateString(),
            'selesai_magang' => now()->addMonths(3)->toDateString(),
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_upload_laporan_hasil_magang(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();
        $this->actingAs($admin);
        $penerimaan = $this->seedPenerimaan();

        $file = UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf');

        $response = $this->post(route('admin.hasil.store'), [
            'penerimaan_id' => $penerimaan->id,
            'laporan_hasil_magang' => $file,
            'catatan' => 'Laporan akhir',
        ]);
        $response->assertRedirect(route('admin.hasil'));

        $this->assertDatabaseHas('hasil_magang', [
            'penerimaan_id' => $penerimaan->id,
            'status' => 'pending',
        ]);

        $record = HasilMagang::first();
        Storage::disk('public')->assertExists($record->laporan_hasil_magang);
    }
}
