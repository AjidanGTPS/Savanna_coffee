<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_material_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_material_id')->constrained()->restrictOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->unsignedInteger('price_per_unit');
            $table->unsignedInteger('total_price');
            $table->string('supplier')->nullable();
            $table->foreignId('purchased_by')->constrained('users')->restrictOnDelete();
            $table->date('purchased_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_material_purchases');
    }
};
