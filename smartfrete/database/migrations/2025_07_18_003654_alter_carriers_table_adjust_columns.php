<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carriers', function (Blueprint $table) {
            // Renomeando pra melhor entendimento
            $table->renameColumn('price', 'final_price');
            $table->renameColumn('deadline', 'deadline_days');

            $table->string('carrier_code')->nullable();
            $table->string('service_code')->nullable();
            $table->decimal('original_price', 12, 2)->nullable();

            // Índice para métricas agrupadas por transportadora
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::table('carriers', function (Blueprint $table) {
            $table->renameColumn('deadline_days', 'deadline');
            $table->renameColumn('final_price', 'price');
            $table->dropColumn(['carrier_code', 'service_code', 'original_price']);
            $table->dropIndex(['name']);
        });
    }
};
