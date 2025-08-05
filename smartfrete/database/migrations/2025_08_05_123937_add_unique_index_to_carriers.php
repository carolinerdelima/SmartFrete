<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('carriers', function (Blueprint $table) {
            $table->unique(['quote_id', 'carrier_code', 'service_code'], 'unique_carrier_by_quote_service');
        });
    }

    public function down(): void
    {
        Schema::table('carriers', function (Blueprint $table) {
            $table->dropUnique('unique_carrier_by_quote_service');
        });
    }
};

