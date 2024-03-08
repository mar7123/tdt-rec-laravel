<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;
    protected $table = "articles";
    protected $primaryKey = "article_id";
    protected $keyType = "integer";
    protected $fillable = [
        "published_at",
        "article_title",
        "article_desc",
        "article_img",
        "author_id",
        "category_id",
    ];
    protected $hidden = [
        "created_at",
        "updated_at",
    ];
    public $incrementing = true;

    // has many
    public function comments(): HasMany
    {
        return $this->hasMany(ArticleComments::class, 'article_id', 'article_id');
    }

    // many to one
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, "author_id", "user_id");
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategories::class, "category_id", "article_category_id");
    }
}
