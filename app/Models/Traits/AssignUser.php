<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Trait AssignUser.
 *
 * @package App\Models\Traits
 * @author annejan@badge.team
 */
trait AssignUser {
    /**
     * Make sure User is assigned to Model.
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(
            function($model) {
                if ($model->user_id === null) {
                    $user = Auth::guard()->user();
                    $model->user()->associate($user);
                }
            }
        );
    }

    /**
     * Get the User that owns this Model.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
