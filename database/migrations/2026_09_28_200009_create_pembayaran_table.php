<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_faktur', 20)->unique();
            $table->foreignId('sesi_meja_id')->constrained('sesi_meja');
            $table->foreignId('kasir_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->unsignedInteger('subtotal')->default(0);
            $table->decimal('persen_pajak', 5, 2)->default(0);
            $table->unsignedInteger('jumlah_pajak')->default(0);
            $table->decimal('persen_layanan', 5, 2)->default(0);
            $table->unsignedInteger('jumlah_layanan')->default(0);
            $table->unsignedInteger('jumlah_diskon')->default(0);
            $table->unsignedInteger('total')->default(0);
            $table->enum('metode', ['tunai', 'kartu', 'dompet_digital'])->default('tunai');
            $table->string('penyedia', 50)->nullable();
            $table->string('nomor_referensi', 100)->nullable();
            $table->unsignedInteger('jumlah_dibayar')->default(0);
            $table->integer('kembalian')->default(0);
            $table->enum('status', ['lunas', 'batal'])->default('lunas');
            $table->timestamp('dibayar_pada')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
