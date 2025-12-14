<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\PengajuanMagang;
use Illuminate\Support\Facades\Hash;

class PenerimaanStoreFormTest extends TestCase
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

    public function test_store_penerimaan_via_form_with_files(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();
        $this->actingAs($admin);

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
            'status' => 'diproses',
        ]);

        $payload = [
            'pengajuan_id' => $pengajuan->id,
            'peserta_nama' => ['Okky','Ali'],
            'peserta_telepon' => ['081234567890','081111111111'],
            'instansi' => 'Universitas Telkom Surabaya',
            'jurusan' => 'Informatika',
            'lokasi_id' => $lokasi->id,
            'mulai_magang' => now()->toDateString(),
            'selesai_magang' => now()->addMonths(3)->toDateString(),
            'surat_pengantar' => UploadedFile::fake()->create('surat.pdf', 50, 'application/pdf'),
            'proposal_magang' => UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf'),
            'ktp_peserta' => UploadedFile::fake()->create('ktp.pdf', 50, 'application/pdf'),
        ];

        $resp = $this->post(route('admin.penerimaan.store'), $payload);
        $resp->assertRedirect();

        $this->assertDatabaseHas('penerimaan', [
            'pengajuan_id' => $pengajuan->id,
            'lokasi_id' => $lokasi->id,
        ]);

        // Ensure files stored
        $rec = \App\Models\Penerimaan::first();
        Storage::disk('public')->assertExists($rec->surat_pengantar_izin);
        Storage::disk('public')->assertExists($rec->proposal_magang);
        Storage::disk('public')->assertExists($rec->ktp_peserta);
    }
}
