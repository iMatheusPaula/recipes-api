<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'name',
        'recipe_id',
        'rating',
        'comment',
        'ip_address'
    ];

    protected $hidden = [
        'ip_address'
    ];

    protected function casts(): array
    {
        return [
            'name' => 'string',
            'recipe_id' => 'integer',
            'rating' => 'integer',
            'comment' => 'string',
            'ip_address' => 'string'
        ];
    }

    /**
     * Get the recipe that owns the review.
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
