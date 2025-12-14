<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Hash;

class LokasiCrudTest extends TestCase
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

    public function test_admin_can_create_lokasi(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $payload = [
            'bidang' => 'Software Development',
            'tim' => 'Tim A',
            'quota' => 5,
            'alamat' => 'Jl. Test 123',
        ];

        $response = $this->post(route('admin.master.lokasi.store'), $payload);
        $response->assertRedirect(route('admin.master.lokasi.index'));

        $this->assertDatabaseHas('lokasi', [
            'bidang' => 'Software Development',
            'tim' => 'Tim A',
            'quota' => 5,
        ]);
    }

    public function test_admin_can_update_lokasi(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $lokasi = Lokasi::create([
            'bidang' => 'Networking',
            'tim' => 'Tim B',
            'quota' => 3,
            'alamat' => 'Alamat Lama',
        ]);

        $payload = [
            'bidang' => 'Networking',
            'tim' => 'Tim B',
            'quota' => 10,
            'alamat' => 'Alamat Baru',
        ];

        $response = $this->put(route('admin.master.lokasi.update', $lokasi->id), $payload);
        $response->assertRedirect(route('admin.master.lokasi.index'));

        $this->assertDatabaseHas('lokasi', [
            'id' => $lokasi->id,
            'quota' => 10,
            'alamat' => 'Alamat Baru',
        ]);
    }

    public function test_admin_can_delete_lokasi(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $lokasi = Lokasi::create([
            'bidang' => 'Security',
            'tim' => 'Tim C',
            'quota' => 2,
            'alamat' => null,
        ]);

        $response = $this->delete(route('admin.master.lokasi.destroy', $lokasi->id));
        $response->assertRedirect(route('admin.master.lokasi.index'));

        $this->assertDatabaseMissing('lokasi', [
            'id' => $lokasi->id,
        ]);
    }
}
