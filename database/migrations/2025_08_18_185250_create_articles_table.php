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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('portal_id');
            $table->foreign('portal_id')->references('id')->on('portals');

            $table->string('hash', 32);
            $table->string('url');
            $table->string('title');
            $table->dateTime('published_at')->nullable();
            $table->string('author')->nullable();
            $table->string('main_image_src')->nullable();
            $table->string('main_image_alt')->nullable();
            $table->string('main_image_author')->nullable();
            $table->text('lead')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
