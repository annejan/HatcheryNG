<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;

/**
 * Class DownloadCounter.
 *
 * @author annejan@badge.team
 */
class DownloadCounter extends Event
{
    use SerializesModels;

    /**
     * @var Project
     */
    public Project $project;

    /**
     * DownloadCounter constructor.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }
}
