<?php

namespace App\Models;

use App\Models\Traits\AssignUser;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Warning.
 *
 * @author annejan@badge.team
 * @property int         $id
 * @property int         $user_id
 * @property int         $project_id
 * @property string      $description
 * @property Carbon|null $deleted_at
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property-read Project $project
 * @property-read User $user
 * @method static bool|null forceDelete()
 * @method static Builder|Warning newModelQuery()
 * @method static Builder|Warning newQuery()
 * @method static Builder|Warning onlyTrashed()
 * @method static Builder|Warning query()
 * @method static bool|null restore()
 * @method static Builder|Warning whereCreatedAt($value)
 * @method static Builder|Warning whereDeletedAt($value)
 * @method static Builder|Warning whereDescription($value)
 * @method static Builder|Warning whereId($value)
 * @method static Builder|Warning whereProjectId($value)
 * @method static Builder|Warning whereUpdatedAt($value)
 * @method static Builder|Warning whereUserId($value)
 * @method static Builder|Warning withTrashed()
 * @method static Builder|Warning withoutTrashed()
 * @mixin Eloquent
 */
class Warning extends Model
{
    use SoftDeletes;
    use HasFactory;
    use AssignUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'project_id', 'description',
    ];

    /**
     * Get the Project that this Vote is for.
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
