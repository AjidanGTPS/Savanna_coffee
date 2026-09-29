<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE pembayaran MODIFY metode ENUM('tunai','kartu','dompet_digital','qris') NOT NULL DEFAULT 'tunai'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pembayaran MODIFY metode ENUM('tunai','kartu','dompet_digital') NOT NULL DEFAULT 'tunai'");
    }
};
