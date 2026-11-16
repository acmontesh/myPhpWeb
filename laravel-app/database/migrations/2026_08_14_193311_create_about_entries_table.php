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
        Schema::create('about_entries', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // education | experience | interests
            $table->string('icon_class')->default('fa-solid fa-circle-check');
            $table->json('title');
            $table->json('detail')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index(['section', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_entries');
    }
};
