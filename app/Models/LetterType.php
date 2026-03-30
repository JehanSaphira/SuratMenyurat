<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LetterType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'template_view',
        'extra_fields',
    ];

    protected $casts = [
        'extra_fields' => 'array',
    ];

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }
}
