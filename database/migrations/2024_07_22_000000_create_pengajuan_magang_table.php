<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_magang', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email');
            $table->string('no_telp');
            $table->string('instansi')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('status')->default('pending'); // pending, diterima, direvisi, ditolak
            $table->text('catatan')->nullable();
            $table->string('file_surat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_magang');
    }
}; 