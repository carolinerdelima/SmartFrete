<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('volumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');
            $table->integer('category');
            $table->integer('amount');
            $table->decimal('unitary_weight', 10, 2);
            $table->decimal('price', 10, 2);
            $table->string('sku');
            $table->decimal('height', 10, 3);
            $table->decimal('width', 10, 3);
            $table->decimal('length', 10, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volumes');
    }
};
