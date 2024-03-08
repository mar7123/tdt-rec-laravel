<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;
    protected $table = "roles";
    protected $primaryKey = "role_id";
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
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'user_role_id', 'role_id');
    }
}
