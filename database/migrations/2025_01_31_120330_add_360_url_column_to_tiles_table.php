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
        Schema::table('tiles', function (Blueprint $table) {
            $table->string('image_variation_3')->nullable()->after('image_variation_2');
            $table->string('design_finish')->nullable()->after('finish');
            $table->string('360_url')->nullable()->after('deletion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tiles', function (Blueprint $table) {
            $table->dropColumn(['image_variation_3','design_finish','360_url']);
        });
    }
};