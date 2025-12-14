<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Progress;
use Illuminate\Support\Facades\Hash;

class ProgressPemagangTest extends TestCase
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

    public function test_upload_progress_pdf_and_delete(): void
    {
        Storage::fake('public');
        $user = $this->createMagang();
        $this->actingAs($user);

        // Upload
        $file = UploadedFile::fake()->create('progress.pdf', 100, 'application/pdf');
        $response = $this->post(route('mg.progress.upload'), [
            'title' => 'Minggu 1',
            'description' => 'Laporan awal',
            'file' => $file,
        ]);
        $response->assertRedirect();

        $record = Progress::first();
        $this->assertNotNull($record);
        $this->assertEquals($user->id, $record->user_id);
        Storage::disk('public')->assertExists($record->file_path);

        // Delete
        $del = $this->delete(route('mg.progress.delete', $record->id));
        $del->assertRedirect();
        $this->assertDatabaseMissing('progress', ['id' => $record->id]);
        Storage::disk('public')->assertMissing($record->file_path);
    }
}
