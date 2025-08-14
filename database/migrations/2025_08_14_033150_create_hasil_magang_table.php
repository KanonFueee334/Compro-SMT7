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
        Schema::create('hasil_magang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penerimaan_id'); // Reference to penerimaan table
            $table->string('laporan_hasil_magang')->nullable(); // File path for internship report
            $table->string('surat_keterangan_selesai')->nullable(); // File path for completion certificate
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->text('catatan')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('penerimaan_id')->references('id')->on('penerimaan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_magang');
    }
};
