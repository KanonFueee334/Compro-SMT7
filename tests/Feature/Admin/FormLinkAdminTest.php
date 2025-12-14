<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\FormLink;
use Illuminate\Support\Facades\Hash;

class FormLinkAdminTest extends TestCase
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

    public function test_admin_can_create_form_link_magang(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $payload = [
            'title' => 'Form Magang Periode 2025',
            'description' => 'Deskripsi',
            'expires_at' => null,
        ];

        $response = $this->post(route('admin.form_links.store'), $payload);
        $response->assertRedirect(route('admin.form_links.index'));

        $this->assertDatabaseHas('form_links', [
            'form_type' => 'magang',
            'title' => 'Form Magang Periode 2025',
        ]);
    }

    public function test_admin_can_toggle_status_form_link_magang(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $link = FormLink::create([
            'form_type' => 'magang',
            'title' => 'Link Uji Toggle',
            'description' => null,
            'is_active' => false,
            'expires_at' => null,
            'created_by' => $admin->id,
        ]);

        $response = $this->post(route('admin.form_links.toggle-status', $link->id));
        $response->assertRedirect(route('admin.form_links.index'));

        $this->assertDatabaseHas('form_links', [
            'id' => $link->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_delete_form_link_magang(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $link = FormLink::create([
            'form_type' => 'magang',
            'title' => 'Link Uji Hapus',
            'description' => null,
            'is_active' => true,
            'expires_at' => null,
            'created_by' => $admin->id,
        ]);

        $response = $this->delete(route('admin.form_links.destroy', $link->id));
        $response->assertRedirect(route('admin.form_links.index'));

        $this->assertDatabaseMissing('form_links', [
            'id' => $link->id,
        ]);
    }
}
