<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->date('date');
            $table->timestamps();

            $table->unique(['menu_id', 'date'], 'unique_menu_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_menus');
    }
};
