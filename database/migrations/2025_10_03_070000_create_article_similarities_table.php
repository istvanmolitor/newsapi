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
        Schema::create('article_similarities', function (Blueprint $table) {
            $table->id();

            // We enforce storing pairs in normalized order (smaller ID first) at application level.
            $table->unsignedBigInteger('article_id_1');
            $table->unsignedBigInteger('article_id_2');

            // Similarity score between 0 and 1 (or more, depending on metric). Using decimal for stability.
            $table->decimal('similarity', 8, 6);

            // Optional: algorithm/method name or version used to compute similarity
            $table->string('method')->nullable();

            // When the similarity was computed
            $table->timestamp('computed_at')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('article_id_1')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('article_id_2')->references('id')->on('articles')->onDelete('cascade');

            // Ensure a pair is unique regardless of direction (with normalized storage).
            $table->unique(['article_id_1', 'article_id_2'], 'article_pair_unique');

            // Fast lookups for all pairs involving a given article
            $table->index('article_id_1');
            $table->index('article_id_2');
        });

        // Optional: add a database-level check to prevent self-similarity rows (if supported by the DB)
        // Laravel's schema builder lacks portable check constraints; using raw SQL where supported.
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            // MySQL 8.0.16+ supports CHECK but historically ignored; use trigger alternative in app layer if needed.
            try {
                Schema::getConnection()->statement('ALTER TABLE article_similarities ADD CONSTRAINT chk_article_ids CHECK (article_id_1 <> article_id_2)');
            } catch (Throwable $e) {
                // Ignore if not supported
            }
        } elseif (Schema::getConnection()->getDriverName() === 'pgsql') {
            Schema::getConnection()->statement('ALTER TABLE article_similarities ADD CONSTRAINT chk_article_ids CHECK (article_id_1 <> article_id_2)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_similarities');
    }
};
