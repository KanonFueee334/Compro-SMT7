<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\FormLink;
use Carbon\Carbon;

class FormLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_url_accessor(): void
    {
        $link = FormLink::create([
            'form_type' => 'magang',
            'title' => 'Test',
            'description' => null,
            'is_active' => true,
            'expires_at' => null,
            'created_by' => null,
        ]);

        $this->assertStringContainsString('/form/', $link->full_url);
        $this->assertNotEmpty(parse_url($link->full_url, PHP_URL_PATH));
    }

    public function test_is_active_true_when_flag_true_and_not_expired(): void
    {
        $link = FormLink::create([
            'form_type' => 'magang',
            'title' => 'Active',
            'description' => null,
            'is_active' => true,
            'expires_at' => Carbon::now()->addDay(),
            'created_by' => null,
        ]);
        $this->assertTrue($link->isActive());
    }

    public function test_is_active_false_when_expired(): void
    {
        $link = FormLink::create([
            'form_type' => 'magang',
            'title' => 'Expired',
            'description' => null,
            'is_active' => true,
            'expires_at' => Carbon::now()->subDay(),
            'created_by' => null,
        ]);
        $this->assertFalse($link->isActive());
    }

    public function test_is_active_false_when_flag_false(): void
    {
        $link = FormLink::create([
            'form_type' => 'magang',
            'title' => 'Inactive',
            'description' => null,
            'is_active' => false,
            'expires_at' => Carbon::now()->addDay(),
            'created_by' => null,
        ]);
        $this->assertFalse($link->isActive());
    }
}
