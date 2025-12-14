<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Skip on SQLite (testing) since ALTER ... MODIFY is MySQL-specific
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        // Gunakan ALTER langsung agar tidak perlu doctrine/dbal
        if (Schema::hasTable('pengajuan_magang')) {
            if (Schema::hasColumn('pengajuan_magang', 'email')) {
                DB::statement("ALTER TABLE pengajuan_magang MODIFY email VARCHAR(255) NULL");
            }
            if (Schema::hasColumn('pengajuan_magang', 'status')) {
                DB::statement("ALTER TABLE pengajuan_magang MODIFY status VARCHAR(50) NOT NULL DEFAULT 'pengajuan'");
            }
            if (Schema::hasColumn('pengajuan_magang', 'catatan')) {
                DB::statement("ALTER TABLE pengajuan_magang MODIFY catatan TEXT NULL");
            }
            if (Schema::hasColumn('pengajuan_magang', 'file_surat')) {
                DB::statement("ALTER TABLE pengajuan_magang MODIFY file_surat VARCHAR(255) NULL");
            }
            if (Schema::hasColumn('pengajuan_magang', 'lokasi_id')) {
                DB::statement("ALTER TABLE pengajuan_magang MODIFY lokasi_id BIGINT UNSIGNED NULL");
            }
        }
    }

    public function down(): void
    {
        // Tidak mengembalikan perubahan untuk menghindari gangguan data
    }
}; 