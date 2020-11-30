<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class TeamTest.
 *
 * @author annejan@badge.team
 */
class TeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Assert the Team has a relation with Projects.
     */
    public function testTeamProjectsRelationship(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        $this->be($user);
        $project = Project::factory()->create();
        $this->assertEmpty($team->projects);
        $team->projects()->attach($project);
        /** @var Team $team */
        $team = Team::find($team->id);
        $this->assertInstanceOf(Collection::class, $team->projects);
        $this->assertInstanceOf(Project::class, $team->projects->first());
        /** @var Project $firstProject */
        $firstProject = $team->projects->first();
        $this->assertEquals($project->id, $firstProject->id);
        $this->assertCount(1, $team->projects);
        $anotherProject = Project::factory()->create();
        $team->projects()->attach($anotherProject);
        /** @var Team $team */
        $team = Team::find($team->id);
        $this->assertCount(2, $team->projects);
    }
}
