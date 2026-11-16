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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('doi')->unique();
            $table->string('type'); // journal|conference
            $table->json('title');
            $table->json('abstract')->nullable();
            $table->string('image')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedInteger('citation_count')->default(0);
            $table->string('citation_source')->default('manual'); // auto|manual
            $table->timestamp('citation_synced_at')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
