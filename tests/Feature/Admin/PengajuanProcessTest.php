<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\PengajuanMagang;
use App\Models\Penerimaan;
use Illuminate\Support\Facades\Hash;

class PengajuanProcessTest extends TestCase
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

    private function makePengajuanWithLokasi(string $status = 'pengajuan'): PengajuanMagang
    {
        $lokasi = Lokasi::create([
            'bidang' => 'Software Development',
            'tim' => 'Tim A',
            'quota' => 10,
            'alamat' => null,
        ]);

        return PengajuanMagang::create([
            'nama_pemohon' => 'Okky',
            'no_hp' => '081234567890',
            'nama_anggota' => 'Ali (HP: 081111111111)',
            'asal_instansi' => 'Universitas Telkom Surabaya',
            'jurusan' => 'Informatika',
            'keahlian' => 'Laravel, PHP',
            'lokasi_id' => $lokasi->id,
            'mulai_magang' => now()->toDateString(),
            'selesai_magang' => now()->addMonths(3)->toDateString(),
            'email' => 'okky@example.com',
            'status' => $status,
            'catatan' => null,
        ]);
    }

    public function test_admin_menerima_pengajuan_membuat_penerimaan(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $pengajuan = $this->makePengajuanWithLokasi('diproses');

        $response = $this->post(route('admin.pengajuan.ubah-status', $pengajuan->id), [
            'status' => 'diterima',
            'catatan' => 'OK',
        ]);
        $response->assertRedirect(route('admin.pengajuan.daftar'));

        $this->assertDatabaseHas('pengajuan_magang', [
            'id' => $pengajuan->id,
            'status' => 'diterima',
        ]);

        $this->assertDatabaseHas('penerimaan', [
            'pengajuan_id' => $pengajuan->id,
            'lokasi_id' => $pengajuan->lokasi_id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_menolak_pengajuan_tidak_membuat_penerimaan(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $pengajuan = $this->makePengajuanWithLokasi('diproses');

        $response = $this->post(route('admin.pengajuan.ubah-status', $pengajuan->id), [
            'status' => 'ditolak',
            'catatan' => 'Tidak sesuai kriteria',
        ]);
        $response->assertRedirect(route('admin.pengajuan.daftar'));

        $this->assertDatabaseHas('pengajuan_magang', [
            'id' => $pengajuan->id,
            'status' => 'ditolak',
        ]);

        $this->assertDatabaseMissing('penerimaan', [
            'pengajuan_id' => $pengajuan->id,
        ]);
    }
}
