<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meja', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 20)->nullable();
            $table->string('nama', 50);
            $table->string('barcode')->unique();
            $table->unsignedTinyInteger('kapasitas')->default(4);
            $table->string('area', 50)->nullable();
            $table->enum('status', ['kosong', 'terisi', 'dipesan'])->default('kosong');
            $table->boolean('aktif')->default(true);
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meja');
    }
};
