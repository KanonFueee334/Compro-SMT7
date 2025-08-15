<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pengajuan_magang')) {
            Schema::create('pengajuan_magang', function (Blueprint $table) {
                $table->id();
                $table->string('nama_pemohon');
                $table->string('no_hp');
                $table->text('nama_anggota')->nullable();
                $table->string('asal_instansi')->nullable();
                $table->string('jurusan')->nullable();
                $table->text('keahlian')->nullable();
                $table->unsignedBigInteger('lokasi_id')->nullable();
                $table->date('mulai_magang')->nullable();
                $table->date('selesai_magang')->nullable();
                $table->string('email')->nullable();
                $table->string('status')->default('pengajuan');
                $table->text('catatan')->nullable();
                $table->string('file_surat')->nullable();
                $table->timestamps();

                $table->foreign('lokasi_id')->references('id')->on('lokasi')->onDelete('set null');
                $table->index(['status', 'lokasi_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_magang');
    }
}; 