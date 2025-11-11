<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'ingredients',
        'instructions',
        'user_id'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['average_rating'];

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

    /**
     * Get the reviews for the recipe.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Creates an average recipe rating attribute
     */
    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: function () {
                $average = $this->reviews()->avg('rating');

                return $average !== null ? (float) $average : null;
            }
        );
    }
}
