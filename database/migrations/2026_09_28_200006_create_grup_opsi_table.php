<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grup_opsi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 100)->unique();
            $table->enum('tipe', ['tunggal', 'ganda'])->default('tunggal');
            $table->boolean('wajib')->default(false);
            $table->unsignedTinyInteger('maks_pilihan')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });

        Schema::create('opsi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grup_opsi_id')->constrained('grup_opsi')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->unsignedInteger('harga_tambahan')->default(0);
            $table->boolean('bawaan')->default(false);
            $table->boolean('tersedia')->default(true);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });

        Schema::create('produk_grup_opsi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->foreignId('grup_opsi_id')->constrained('grup_opsi')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->unique(['produk_id', 'grup_opsi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_grup_opsi');
        Schema::dropIfExists('opsi');
        Schema::dropIfExists('grup_opsi');
    }
};
