<?php

namespace App\Models;

use App\Models\Traits\SlugRouting;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Badge.
 *
 * @author annejan@badge.team
 * @property int         $id
 * @property string      $name
 * @property string      $slug
 * @property string|null $constraints
 * @property string|null $commands
 * @property Carbon|null $deleted_at
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property-read Collection|Project[] $projects
 * @property-read int|null $projects_count
 * @property-read Collection|BadgeProject[] $states
 * @property-read int|null $states_count
 * @method static Builder|Badge newModelQuery()
 * @method static Builder|Badge newQuery()
 * @method static Builder|Badge query()
 * @method static Builder|Badge whereCreatedAt($value)
 * @method static Builder|Badge whereDeletedAt($value)
 * @method static Builder|Badge whereId($value)
 * @method static Builder|Badge whereName($value)
 * @method static Builder|Badge whereSlug($value)
 * @method static Builder|Badge whereUpdatedAt($value)
 * @method static Builder|Badge whereCommands($value)
 * @method static Builder|Badge whereConstraints($value)
 * @mixin Eloquent
 */
class Badge extends Model
{
    use HasFactory;
    use SlugRouting;

    /**
     * @return BelongsToMany
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function states(): HasMany
    {
        return $this->hasMany(BadgeProject::class);
    }
}
