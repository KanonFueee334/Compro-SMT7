<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\PengajuanMagang;
use App\Models\Penerimaan;
use Illuminate\Support\Facades\Hash;

class PenerimaanAdminTest extends TestCase
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

    public function test_admin_can_update_status_penerimaan(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);
        $penerimaan = $this->seedPenerimaan();

        $response = $this->post(route('admin.penerimaan.update-status', $penerimaan->id), [
            'status' => 'approved',
            'catatan' => 'Diterima',
        ]);
        $response->assertRedirect(route('admin.penerimaan.index'));

        $this->assertDatabaseHas('penerimaan', [
            'id' => $penerimaan->id,
            'status' => 'approved',
            'catatan' => 'Diterima',
        ]);
    }

    public function test_admin_can_delete_penerimaan_and_files(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();
        $this->actingAs($admin);
        $penerimaan = $this->seedPenerimaan();

        // Simulasikan file yang ada
        $surat = Storage::disk('public')->put('penerimaan/surat_pengantar', 'dummy');
        $proposal = Storage::disk('public')->put('penerimaan/proposal_magang', 'dummy');
        $ktp = Storage::disk('public')->put('penerimaan/ktp_peserta', 'dummy');

        $penerimaan->update([
            'surat_pengantar_izin' => $surat,
            'proposal_magang' => $proposal,
            'ktp_peserta' => $ktp,
        ]);

        $response = $this->delete(route('admin.penerimaan.destroy', $penerimaan->id));
        $response->assertRedirect(route('admin.penerimaan.index'));

        $this->assertDatabaseMissing('penerimaan', ['id' => $penerimaan->id]);
        Storage::disk('public')->assertMissing($surat);
        Storage::disk('public')->assertMissing($proposal);
        Storage::disk('public')->assertMissing($ktp);
    }
}
