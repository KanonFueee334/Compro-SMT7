<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AbsensiEdgeCaseTest extends TestCase
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

    public function test_masuk_terlambat_and_pulang_awal_labels(): void
    {
        $user = $this->createMagang();
        $this->actingAs($user);

        // Set time to after 08:00 for late check-in
        Carbon::setTestNow(Carbon::parse('2025-01-01 08:05:00', 'Asia/Jakarta'));
        $this->post(route('mg.absen.masuk'))->assertRedirect();

        // Set time to before 16:00 for early check-out
        Carbon::setTestNow(Carbon::parse('2025-01-01 15:30:00', 'Asia/Jakarta'));
        $this->post(route('mg.absen.pulang'))->assertRedirect();

        // View history and assert labels
        $resp = $this->get(route('mg.absen.history'));
        $resp->assertStatus(200);
        $resp->assertSee('Masuk Terlambat');
        $resp->assertSee('Pulang Awal');

        // Clear test now
        Carbon::setTestNow();
    }
}
