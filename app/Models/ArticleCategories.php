<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleCategories extends Model
{
    use HasFactory;
    protected $table = "article_categories";
    protected $primaryKey = "article_category_id";
    protected $keyType = "integer";
    protected $fillable = [
        'name',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public $incrementing = true;
    public $timestamps = true;

    // has many
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id', 'article_category_id');
    }
}
