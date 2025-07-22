<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_magang', 'email')) {
                $table->string('email')->nullable()->after('nama_pemohon');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'status')) {
                $table->string('status')->default('pending')->after('jurusan');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'catatan')) {
                $table->text('catatan')->nullable()->after('status');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'file_surat')) {
                $table->string('file_surat')->nullable()->after('catatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_magang', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('pengajuan_magang', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('pengajuan_magang', 'catatan')) {
                $table->dropColumn('catatan');
            }
            if (Schema::hasColumn('pengajuan_magang', 'file_surat')) {
                $table->dropColumn('file_surat');
            }
        });
    }
}; 