<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip on SQLite (testing) since ALTER ... MODIFY/ENUM is MySQL-specific
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        // Add 'selesai' to enum list for penerimaan.status
        DB::statement("ALTER TABLE `penerimaan` MODIFY `status` ENUM('pending','approved','rejected','selesai') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip on SQLite
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        // Revert to original enum without 'selesai'
        DB::statement("ALTER TABLE `penerimaan` MODIFY `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
    }
};
