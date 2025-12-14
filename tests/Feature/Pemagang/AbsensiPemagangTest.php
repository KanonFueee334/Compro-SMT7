<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Absensi;
use Illuminate\Support\Facades\Hash;

class AbsensiPemagangTest extends TestCase
{
    use RefreshDatabase;

    private function createMagang(): User
    {
        return User::create([
            'username' => 'ali',
            'password' => Hash::make('password123'),
            'name' => 'Ali',
            'role' => 'magang',
            'status' => 1,
        ]);
    }

    public function test_magang_can_open_home(): void
    {
        $user = $this->createMagang();
        $this->actingAs($user);

        $response = $this->get(route('mg.home'));
        $response->assertStatus(200);
    }

    public function test_absen_masuk_once_only(): void
    {
        $user = $this->createMagang();
        $this->actingAs($user);

        // First check-in
        $resp1 = $this->post(route('mg.absen.masuk'));
        $resp1->assertRedirect();
        $this->assertDatabaseCount('absensi', 1);

        // Second check-in should be blocked
        $resp2 = $this->post(route('mg.absen.masuk'));
        $resp2->assertRedirect();
        $this->assertDatabaseCount('absensi', 1);
    }

    public function test_absen_pulang_after_masuk_and_only_once(): void
    {
        $user = $this->createMagang();
        $this->actingAs($user);

        // Without check-in, pulang should be blocked
        $resp0 = $this->post(route('mg.absen.pulang'));
        $resp0->assertRedirect();
        $this->assertDatabaseCount('absensi', 0);

        // Do check-in
        $this->post(route('mg.absen.masuk'))->assertRedirect();
        $this->assertDatabaseCount('absensi', 1);

        // Do check-out
        $this->post(route('mg.absen.pulang'))->assertRedirect();
        $this->assertDatabaseCount('absensi', 2);

        // Second check-out should be blocked
        $this->post(route('mg.absen.pulang'))->assertRedirect();
        $this->assertDatabaseCount('absensi', 2);
    }
}
