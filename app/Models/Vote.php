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
 * Class Vote.
 *
 * @author annejan@badge.team
 * @property int         $id
 * @property int         $user_id
 * @property int         $project_id
 * @property string      $type
 * @property string|null $comment
 * @property Carbon|null $deleted_at
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property-read Project $project
 * @property-read User $user
 * @method static bool|null forceDelete()
 * @method static Builder|Vote newModelQuery()
 * @method static Builder|Vote newQuery()
 * @method static Builder|Vote onlyTrashed()
 * @method static Builder|Vote query()
 * @method static bool|null restore()
 * @method static Builder|Vote whereComment($value)
 * @method static Builder|Vote whereCreatedAt($value)
 * @method static Builder|Vote whereDeletedAt($value)
 * @method static Builder|Vote whereId($value)
 * @method static Builder|Vote whereProjectId($value)
 * @method static Builder|Vote whereType($value)
 * @method static Builder|Vote whereUpdatedAt($value)
 * @method static Builder|Vote whereUserId($value)
 * @method static Builder|Vote withTrashed()
 * @method static Builder|Vote withoutTrashed()
 * @mixin Eloquent
 */
class Vote extends Model
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
        'project_id', 'type',
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
