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
        Schema::create('theme_image_slots', function (Blueprint $table) {
            $table->id();
            $table->string('theme_slug');
            $table->string('slot_key');
            $table->string('label');
            $table->string('current_image_path')->nullable();
            $table->string('default_image_path')->nullable();
            $table->timestamps();

            $table->unique(['theme_slug', 'slot_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_image_slots');
    }
};
