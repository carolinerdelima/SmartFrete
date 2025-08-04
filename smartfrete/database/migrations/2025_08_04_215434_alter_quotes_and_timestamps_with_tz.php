<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Índice para status
            $table->index('status');

            // Convertendo os timestamps para timestamps com fuso horário - impede de dar erro na inserção manual
            $table->timestampTz('created_at')->change();
            $table->timestampTz('updated_at')->change();
        });

        Schema::table('carriers', function (Blueprint $table) {
            $table->timestampTz('created_at')->change();
            $table->timestampTz('updated_at')->change();
        });

        Schema::table('volumes', function (Blueprint $table) {
            $table->timestampTz('created_at')->change();
            $table->timestampTz('updated_at')->change();
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->timestamp('created_at')->change();
            $table->timestamp('updated_at')->change();
        });

        Schema::table('carriers', function (Blueprint $table) {
            $table->timestamp('created_at')->change();
            $table->timestamp('updated_at')->change();
        });

        Schema::table('volumes', function (Blueprint $table) {
            $table->timestamp('created_at')->change();
            $table->timestamp('updated_at')->change();
        });
    }
};
