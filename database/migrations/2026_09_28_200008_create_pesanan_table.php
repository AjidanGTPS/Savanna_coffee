<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan', 20)->unique();
            $table->foreignId('sesi_meja_id')->constrained('sesi_meja');
            $table->foreignId('meja_id')->constrained('meja');
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->enum('status', ['baru', 'dimasak', 'siap', 'diantar', 'dibatalkan'])->default('baru');
            $table->text('catatan')->nullable();
            $table->unsignedInteger('subtotal')->default(0);
            $table->timestamp('dipesan_pada')->nullable();
            $table->timestamp('dimasak_pada')->nullable();
            $table->timestamp('siap_pada')->nullable();
            $table->timestamp('diantar_pada')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });

        Schema::create('item_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('produk_id')->nullable()->constrained('produk')->nullOnDelete();
            $table->foreignId('varian_produk_id')->nullable()->constrained('varian_produk')->nullOnDelete();
            $table->string('nama_produk', 150);
            $table->string('nama_varian', 100)->nullable();
            $table->unsignedSmallInteger('jumlah')->default(1);
            $table->unsignedInteger('harga_satuan')->default(0);
            $table->unsignedInteger('harga_opsi')->default(0);
            $table->unsignedInteger('subtotal')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });

        Schema::create('opsi_item_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_pesanan_id')->constrained('item_pesanan')->cascadeOnDelete();
            $table->foreignId('opsi_id')->nullable()->constrained('opsi')->nullOnDelete();
            $table->string('nama_grup_opsi', 100);
            $table->string('nama_opsi', 100);
            $table->unsignedInteger('harga_tambahan')->default(0);
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });

        Schema::create('log_status_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->string('status_awal', 20)->nullable();
            $table->string('status_akhir', 20);
            $table->timestamp('dibuat_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_status_pesanan');
        Schema::dropIfExists('opsi_item_pesanan');
        Schema::dropIfExists('item_pesanan');
        Schema::dropIfExists('pesanan');
    }
};
