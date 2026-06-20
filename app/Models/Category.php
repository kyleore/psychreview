<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'color', 'description'];

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }
}
