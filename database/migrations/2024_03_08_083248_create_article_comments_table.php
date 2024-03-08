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
        Schema::create('article_comments', function (Blueprint $table) {
            $table->integer('article_comment_id')->autoIncrement();
            $table->timestamp('published_at')->nullable();
            $table->text('comment_desc');
            $table->uuid('author_id');
            $table->foreign('author_id')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('article_id');
            $table->foreign('article_id')->references('article_id')->on('articles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_comments');
    }
};
