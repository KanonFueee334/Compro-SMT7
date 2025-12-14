<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AbsensiPagesPemagangTest extends TestCase
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

    public function test_history_page_accessible(): void
    {
        $user = $this->createMagang();
        $this->actingAs($user);

        $response = $this->get(route('mg.absen.history'));
        $response->assertStatus(200);
    }

    public function test_recap_endpoints_accessible(): void
    {
        $user = $this->createMagang();
        $this->actingAs($user);

        $start = now()->subDays(7)->toDateString();
        $end = now()->toDateString();

        $resp1 = $this->get(route('mg.recap', [$start, $end]));
        $resp1->assertStatus(200);

        $resp2 = $this->followingRedirects()->post(route('mg.recap.m'), [
            'input-year' => now()->year,
            'input-month' => now()->month,
        ]);
        $resp2->assertStatus(200);
    }
}
