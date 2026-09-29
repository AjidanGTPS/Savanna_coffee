<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sesi_meja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meja_id')->constrained('meja');
            $table->foreignId('dibuka_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->string('nama_pelanggan', 100)->nullable();
            $table->unsignedTinyInteger('jumlah_tamu')->nullable();
            $table->enum('status', ['buka', 'dibayar', 'ditutup'])->default('buka');
            $table->timestamp('dibuka_pada')->nullable();
            $table->timestamp('ditutup_pada')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_meja');
    }
};
