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
            $table->integer('article_id')->autoIncrement();
            $table->timestamp('published_at')->nullable();
            $table->text('article_title');
            $table->text('article_desc');
            $table->text('article_img')->nullable();
            $table->uuid('author_id');
            $table->foreign('author_id')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('category_id');
            $table->foreign('category_id')->references('article_category_id')->on('article_categories')->cascadeOnUpdate()->cascadeOnDelete();
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
