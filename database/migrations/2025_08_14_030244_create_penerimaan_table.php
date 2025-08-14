<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penerimaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id'); // Reference to pengajuan_magang
            $table->json('peserta_magang'); // Array of participants with name and phone
            $table->string('instansi_sekolah_universitas');
            $table->string('jurusan');
            $table->unsignedBigInteger('lokasi_id'); // Reference to master lokasi
            $table->date('mulai_magang');
            $table->date('selesai_magang');
            $table->string('surat_pengantar_izin')->nullable(); // File path for cover letter
            $table->string('proposal_magang')->nullable(); // File path for proposal
            $table->string('ktp_peserta')->nullable(); // File path for ID card
            $table->string('surat_penerimaan')->nullable(); // File path for acceptance letter
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('pengajuan_id')->references('id')->on('pengajuan_magang')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('id')->on('lokasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan');
    }
};
