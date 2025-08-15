<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_magang', 'nama_pemohon')) {
                $table->string('nama_pemohon')->nullable()->after('id');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'no_hp')) {
                $table->string('no_hp')->nullable()->after('nama_pemohon');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'asal_instansi')) {
                $table->string('asal_instansi')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'keahlian')) {
                $table->text('keahlian')->nullable()->after('jurusan');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'nama_anggota')) {
                $table->text('nama_anggota')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'mulai_magang')) {
                $table->date('mulai_magang')->nullable()->after('lokasi_id');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'selesai_magang')) {
                $table->date('selesai_magang')->nullable()->after('mulai_magang');
            }
        });

        // Migrasi data dari kolom lama jika ada
        if (Schema::hasColumn('pengajuan_magang', 'nama')) {
            DB::statement("UPDATE pengajuan_magang SET nama_pemohon = nama WHERE (nama_pemohon IS NULL OR nama_pemohon = '') AND nama IS NOT NULL");
        }
        if (Schema::hasColumn('pengajuan_magang', 'no_telp')) {
            DB::statement("UPDATE pengajuan_magang SET no_hp = no_telp WHERE (no_hp IS NULL OR no_hp = '') AND no_telp IS NOT NULL");
        }
        if (Schema::hasColumn('pengajuan_magang', 'instansi')) {
            DB::statement("UPDATE pengajuan_magang SET asal_instansi = instansi WHERE (asal_instansi IS NULL OR asal_instansi = '') AND instansi IS NOT NULL");
        }
    }

    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_magang', 'nama_pemohon')) {
                $table->dropColumn('nama_pemohon');
            }
            if (Schema::hasColumn('pengajuan_magang', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
            if (Schema::hasColumn('pengajuan_magang', 'asal_instansi')) {
                $table->dropColumn('asal_instansi');
            }
            if (Schema::hasColumn('pengajuan_magang', 'keahlian')) {
                $table->dropColumn('keahlian');
            }
            if (Schema::hasColumn('pengajuan_magang', 'nama_anggota')) {
                $table->dropColumn('nama_anggota');
            }
            if (Schema::hasColumn('pengajuan_magang', 'mulai_magang')) {
                $table->dropColumn('mulai_magang');
            }
            if (Schema::hasColumn('pengajuan_magang', 'selesai_magang')) {
                $table->dropColumn('selesai_magang');
            }
        });
    }
}; 