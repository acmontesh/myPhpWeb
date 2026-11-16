<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        $this->backfillSlugs();
    }

    private function backfillSlugs(): void
    {
        $existingSlugs = [];

        foreach (DB::table('news')->orderBy('id')->get(['id', 'title']) as $row) {
            $title = json_decode($row->title, true);
            $base = Str::slug($title['en'] ?? $title[array_key_first($title ?: ['item'])] ?? 'news-item') ?: 'news-item';
            $slug = $base;
            $suffix = 2;

            while (in_array($slug, $existingSlugs, true)) {
                $slug = "{$base}-{$suffix}";
                $suffix++;
            }

            $existingSlugs[] = $slug;
            DB::table('news')->where('id', $row->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
