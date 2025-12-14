<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
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

    public function test_login_page_guest_ok_and_logged_in_redirects(): void
    {
        $resp = $this->get(route('login'));
        $resp->assertStatus(200);

        $admin = $this->createAdmin();
        $this->actingAs($admin);
        $resp2 = $this->get(route('login'));
        $resp2->assertRedirect(route('admin.home'));
    }

    public function test_admin_login_success_and_redirect_to_admin_home(): void
    {
        $admin = $this->createAdmin();
        $resp = $this->post(route('auth'), [
            'nama_pengguna' => 'admin',
            'kata_sandi' => 'admin123',
        ]);
        $resp->assertRedirect('/admin/home');
    }

    public function test_magang_login_success_and_redirect_to_mg_home(): void
    {
        $user = $this->createMagang();
        $resp = $this->post(route('auth'), [
            'nama_pengguna' => 'ali',
            'kata_sandi' => 'password123',
        ]);
        $resp->assertRedirect('/mg-home');
    }

    public function test_login_validation_errors_when_empty(): void
    {
        $resp = $this->post(route('auth'), [
            'nama_pengguna' => '',
            'kata_sandi' => '',
        ]);
        $resp->assertRedirect();
        $resp->assertSessionHasErrors(['nama_pengguna', 'kata_sandi']);
    }

    public function test_login_fail_wrong_password_and_unknown_user(): void
    {
        $admin = $this->createAdmin();

        // Wrong password
        $resp1 = $this->post(route('auth'), [
            'nama_pengguna' => 'admin',
            'kata_sandi' => 'wrong',
        ]);
        $resp1->assertRedirect();
        $resp1->assertSessionHas('error', 'Password salah');

        // Unknown user
        $resp2 = $this->post(route('auth'), [
            'nama_pengguna' => 'unknown',
            'kata_sandi' => 'anything',
        ]);
        $resp2->assertRedirect();
        $resp2->assertSessionHas('error', 'Username tidak ditemukan');
    }

    public function test_logout_redirects_to_login_and_clears_session(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $resp = $this->get(route('logout'));
        $resp->assertRedirect(route('login'));
    }

    public function test_guest_accessing_admin_routes_redirects_to_login(): void
    {
        $resp = $this->get(route('admin.home'));
        $resp->assertRedirect(route('login'));
    }
}
