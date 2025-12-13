<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_menus', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
            $table->dropForeign(['school_id']);
            $table->dropUnique('unique_menu_date');

            $table->unique(['school_id', 'date'], 'unique_school_date');

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('daily_menus', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
            $table->dropForeign(['school_id']);
            $table->dropUnique('unique_school_date');

            $table->unique(['menu_id', 'date'], 'unique_menu_date');

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }
};
