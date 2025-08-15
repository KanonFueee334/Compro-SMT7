<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Gunakan SQL langsung agar tidak perlu doctrine/dbal
        if (Schema::hasColumn('pengajuan_magang', 'nama')) {
            DB::statement("ALTER TABLE pengajuan_magang MODIFY nama VARCHAR(255) NULL");
        }
        if (Schema::hasColumn('pengajuan_magang', 'no_telp')) {
            DB::statement("ALTER TABLE pengajuan_magang MODIFY no_telp VARCHAR(50) NULL");
        }
        if (Schema::hasColumn('pengajuan_magang', 'instansi')) {
            DB::statement("ALTER TABLE pengajuan_magang MODIFY instansi VARCHAR(255) NULL");
        }
        if (Schema::hasColumn('pengajuan_magang', 'jurusan')) {
            DB::statement("ALTER TABLE pengajuan_magang MODIFY jurusan VARCHAR(255) NULL");
        }
    }

    public function down(): void
    {
        // Tidak perlu mengembalikan ke NOT NULL untuk keamanan data
    }
}; 