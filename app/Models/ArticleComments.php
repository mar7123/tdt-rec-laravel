<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleComments extends Model
{
    use HasFactory;
    protected $table = "article_comments";
    protected $primaryKey = "article_comment_id";
    protected $keyType = "integer";
    protected $fillable = [
        "published_at",
        "comment_desc",
        "author_id",
        "article_id",
    ];
    protected $hidden = [
        "created_at",
        "updated_at",
    ];
    public $incrementing = true;

    // many to one
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, "author_id", "user_id");
    }
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, "article_id", "article_id");
    }
}
