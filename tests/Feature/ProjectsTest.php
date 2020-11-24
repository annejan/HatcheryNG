<?php

namespace Tests\Feature;

use App\Events\ProjectUpdated;
use App\Mail\ProjectNotificationMail;
use App\Models\Badge;
use App\Models\Category;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Models\Version;
use App\Models\Warning;
use App\Support\Helpers;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class ProjectsTest.
 *
 * @author annejan@badge.team
 */
class ProjectsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check the projects list.
     */
    public function testProjectsIndexPublic(): void
    {
        $response = $this
            ->get('/projects');
        $response->assertStatus(200)
            ->assertViewHas('projects', Project::paginate());
    }

    /**
     * Check the projects list as a user.
     */
    public function testProjectsIndex(): void
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get('/projects');
        $response->assertStatus(200)
            ->assertViewHas('projects', Project::paginate());
    }

    /**
     * Check the projects can be viewed (publicly).
     */
    public function testProjectsView(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        $project = Project::factory()->create();
        $response = $this
            ->call('get', '/projects/'.$project->slug);
        $response->assertStatus(200)->assertViewHas(['project']);
    }

    /**
     * Check the projects list.
     */
    public function testProjectsIndexBadge(): void
    {
        $badge = Badge::factory()->create();
        $response = $this
            ->get('/projects?badge='.$badge->slug);
        $response->assertStatus(200)
            ->assertViewHas('projects')
            ->assertViewHas('badge', $badge->slug);
    }

    /**
     * Check the projects list.
     */
    public function testProjectsIndexCategory(): void
    {
        $category = Category::factory()->create();
        $response = $this
            ->get('/projects?category='.$category->slug);
        $response->assertStatus(200)
            ->assertViewHas('category', $category->slug)
            ->assertViewHas('projects');
    }

    /**
     * Check the projects list.
     */
    public function testProjectsIndexCategoryBadge(): void
    {
        $badge = Badge::factory()->create();
        $category = Category::factory()->create();
        $response = $this
            ->get('/projects?badge='.$badge->slug.'&category='.$category->slug);
        $response->assertStatus(200)
            ->assertViewHas('badge', $badge->slug)
            ->assertViewHas('category', $category->slug)
            ->assertViewHas('projects');
    }

    /**
     * Search the projects list.
     */
    public function testProjectsIndexSearch(): void
    {
        $response = $this
            ->post('/search', [
                'search' => 'meaning',
            ]);
        $response->assertStatus(200)
            ->assertViewHas('search', 'meaning')
            ->assertViewHas('projects');
    }
}
