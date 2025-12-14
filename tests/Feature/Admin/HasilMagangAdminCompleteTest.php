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

class HasilMagangAdminCompleteTest extends TestCase
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

    private function seedHasilMagang(): HasilMagang
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

        $penerimaan = Penerimaan::create([
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

        return HasilMagang::create([
            'penerimaan_id' => $penerimaan->id,
            'laporan_hasil_magang' => 'laporan_hasil_magang/dummy.pdf',
            'status' => 'pending',
        ]);
    }

    public function test_admin_upload_surat_keterangan_selesai(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $hasil = $this->seedHasilMagang();

        $file = UploadedFile::fake()->create('surat_selesai.pdf', 80, 'application/pdf');

        $resp = $this->post(route('admin.hasil.upload-surat-keterangan', $hasil->id), [
            'surat_keterangan_selesai' => $file,
            'catatan' => 'Selesai dengan baik',
        ]);
        $resp->assertRedirect(route('admin.hasil'));

        $this->assertDatabaseHas('hasil_magang', [
            'id' => $hasil->id,
            'status' => 'completed',
            'catatan' => 'Selesai dengan baik',
        ]);

        $updated = HasilMagang::find($hasil->id);
        Storage::disk('public')->assertExists($updated->surat_keterangan_selesai);
    }
}
