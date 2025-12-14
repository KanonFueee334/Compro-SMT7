<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\PengajuanMagang;
use App\Models\Penerimaan;
use Illuminate\Support\Facades\Hash;

class HasilMagangPemagangTest extends TestCase
{
    use RefreshDatabase;

    private function createMagang(): User
    {
        return User::create([
            'username' => 'ali',
            'password' => Hash::make('password123'),
            'name' => 'Ali',
            'phone' => '081111111111',
            'role' => 'magang',
            'status' => 1,
        ]);
    }

    private function seedPenerimaanForUser(User $user): Penerimaan
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
                ['nama' => $user->name, 'telepon' => $user->phone],
            ],
            'instansi_sekolah_universitas' => 'Universitas Telkom Surabaya',
            'jurusan' => 'Informatika',
            'lokasi_id' => $lokasi->id,
            'mulai_magang' => now()->toDateString(),
            'selesai_magang' => now()->addMonths(3)->toDateString(),
            'status' => 'pending',
        ]);
    }

    public function test_magang_can_view_hasil_when_listed_as_peserta(): void
    {
        $user = $this->createMagang();
        $this->seedPenerimaanForUser($user);
        $this->actingAs($user);

        $resp = $this->get(route('mg.hasil'));
        $resp->assertStatus(200);
    }
}
