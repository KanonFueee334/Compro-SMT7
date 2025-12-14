<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminMiddlewareTest extends TestCase
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

    public function test_non_admin_is_blocked_from_admin_routes(): void
    {
        $user = $this->createMagang();
        $this->actingAs($user);

        $adminRoutes = [
            route('admin.home'),
            route('admin.master.user'),
            route('admin.master.lokasi.index'),
            route('admin.form_links.index'),
            route('admin.penerimaan.index'),
        ];

        foreach ($adminRoutes as $url) {
            $response = $this->get($url);
            $response->assertStatus(403);
        }
    }
}
