<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Widen to VARCHAR first so data update is unrestricted, then narrow to new enum
        DB::statement("ALTER TABLE pengguna MODIFY peran VARCHAR(30) NOT NULL DEFAULT 'kasir'");
        DB::table('pengguna')->where('peran', 'barista')->update(['peran' => 'manajer']);
        DB::table('pengguna')->where('peran', 'dapur')->update(['peran' => 'owner']);
        DB::statement("ALTER TABLE pengguna MODIFY peran ENUM('admin','kasir','pelayan','owner','manajer') NOT NULL DEFAULT 'kasir'");

        // Bahan baku
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('satuan', 30); // kg, liter, pcs, dll
            $table->decimal('stok_saat_ini', 10, 2)->default(0);
            $table->decimal('stok_minimum',  10, 2)->default(0);
            $table->decimal('stok_maksimum', 10, 2)->nullable();
            $table->unsignedInteger('harga_per_satuan')->default(0);
            $table->string('keterangan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });

        Schema::create('transaksi_bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->cascadeOnDelete();
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->enum('jenis', ['masuk', 'keluar', 'penyesuaian']);
            $table->decimal('jumlah', 10, 2);
            $table->decimal('stok_sebelum', 10, 2)->default(0);
            $table->decimal('stok_sesudah', 10, 2)->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamp('dicatat_pada')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_bahan_baku');
        Schema::dropIfExists('bahan_baku');
        DB::statement("ALTER TABLE pengguna MODIFY peran ENUM('admin','kasir','pelayan','barista','dapur') NOT NULL DEFAULT 'kasir'");
    }
};
