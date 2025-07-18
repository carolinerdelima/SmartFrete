<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Garante a extensão de UUIDs
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');

        Schema::table('quotes', function (Blueprint $table) {
            $table->uuid('uuid')
                  ->unique()
                  ->default(DB::raw('uuid_generate_v4()'))
                  ->after('id');

            $table->enum('status', ['pending','success','failed'])
                  ->default('pending')
                  ->after('recipient_zipcode');

            $table->jsonb('frete_rapido_request')->nullable();
            $table->jsonb('frete_rapido_response')->nullable();
            $table->integer('response_time_ms')->nullable();

            $table->index('recipient_zipcode');   // agiliza filtros/ consultas
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropIndex(['recipient_zipcode']);
            $table->dropColumn([
                'uuid',
                'status',
                'frete_rapido_request',
                'frete_rapido_response',
                'response_time_ms',
            ]);
        });
    }
};
