<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['type', 'body', 'sort_order', 'active'];
    protected $casts = ['active' => 'boolean'];

    public function scopeForType($query, string $type)
    {
        return $query->where('type', $type)->where('active', true)->orderBy('sort_order');
    }
}
