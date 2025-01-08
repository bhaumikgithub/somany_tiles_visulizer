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
        Schema::table('companies', function (Blueprint $table) {
            $table->date('last_fetch_date_from_api')->nullable()->after('maximum_tiles');
            $table->integer('fetch_products_count')->nullable()->after('last_fetch_date_from_api');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['last_fetch_date_from_api','fetch_products_count']);
        });
    }
};
