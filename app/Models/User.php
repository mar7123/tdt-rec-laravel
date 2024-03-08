<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $table = "users";
    protected $primaryKey = "user_id";
    protected $keyType = "string";
    protected $fillable = [
        'name',
        'email',
    ];
    protected $hidden = [
        'user_id',
        'salt',
        'password',
        'remember_token',
        'user_role_id',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = [
        'type',
    ];
    public $incrementing = false;
    public $timestamps = true;

    // has many
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id', 'user_id');
    }

    // many to one
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, "user_role_id", "role_id");
    }

    // appended attribute
    protected function getTypeAttribute()
    {
        $type = $this->role()->first();
        return $type->name;
    }
}
