<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProgressNegativeUploadTest extends TestCase
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

    public function test_reject_non_pdf_file(): void
    {
        Storage::fake('public');
        $user = $this->createMagang();
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('image.png', 50, 'image/png');

        $resp = $this->post(route('mg.progress.upload'), [
            'title' => 'Minggu 1',
            'description' => 'Invalid file',
            'file' => $file,
        ]);

        // Harus redirect back dengan error validation
        $resp->assertRedirect();
        $resp->assertSessionHasErrors(['file']);
    }
}
