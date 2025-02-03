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
        Schema::table('room2ds', function (Blueprint $table) {
            $table->string('theme0')->nullable()->after('image');
            $table->string('theme_thumbnail0')->nullable()->after('theme0');
            $table->string('text0')->nullable()->after('theme_thumbnail0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room2ds', function (Blueprint $table) {
            $table->dropColumn(['theme0','theme_thumbnail0', 'text0']);
        });
    }
};
