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
        Schema::table('panoramas', function (Blueprint $table) {
            $table->string('theme0')->nullable()->after('image');
            $table->string('theme_thumbnail0')->nullable()->after('theme0');
            $table->string('text0')->nullable()->after('theme_thumbnail0');

            $table->string('theme1', '1000')->nullable()->after('enabled');
            $table->string('theme_thumbnail1')->nullable()->after('theme1');
            $table->string('text1')->nullable()->after('theme_thumbnail1');

            $table->string('theme2', '1000')->nullable()->after('text1');
            $table->string('theme_thumbnail2')->nullable()->after('theme2');
            $table->string('text2')->nullable()->after('theme_thumbnail2');

            $table->string('theme3', '1000')->nullable()->after('text2');
            $table->string('theme_thumbnail3')->nullable()->after('theme3');
            $table->string('text3')->nullable()->after('theme_thumbnail3');

            $table->string('theme4', '1000')->nullable()->after('text3');
            $table->string('theme_thumbnail4')->nullable()->after('theme4');
            $table->string('text4')->nullable()->after('theme_thumbnail4');

            $table->string('theme5', '1000')->nullable()->after('text4');
            $table->string('theme_thumbnail5')->nullable()->after('theme5');
            $table->string('text5')->nullable()->after('theme_thumbnail5');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panoramas', function (Blueprint $table) {
            $table->dropColumn(['theme0','theme_thumbnail0', 'text0','theme1', 'theme_thumbnail1','text1', 'theme2', 'theme_thumbnail2','text2',
                'theme3', 'theme_thumbnail3','text3', 'theme4', 'theme_thumbnail4', 'text4', 'theme5','theme_thumbnail5', 'text5']);
        });
    }
};
