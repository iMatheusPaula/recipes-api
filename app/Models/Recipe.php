<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    protected $fillable = [
        'name',
        'description',
        'ingredients',
        'instructions',
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'name' => 'string',
            'description' => 'string',
            'ingredients' => 'string',
            'instructions' => 'string',
            'user_id' => 'integer',
        ];
    }

    /**
     * Get the user that owns the recipe.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
