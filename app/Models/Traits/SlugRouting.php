<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

/**
 * Trait SlugRouting.
 *
 * @package App\Models\Traits
 * @author annejan@badge.team
 */
trait SlugRouting {
    /**
     * Generate a slug on save.
     */
    public static function boot(): void
    {
        parent::boot();

        static::saving(
            function($model) {
                $model->slug = Str::slug($model->name, '_');
            }
        );
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
