<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class BadgesTest.
 *
 * @author annejan@badge.team
 */
class BadgesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check the badge index page functions.
     */
    public function testBadgesIndex(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        Badge::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get('/badges');
        $response->assertStatus(200)
            ->assertViewHas('badges', Badge::paginate());
    }

    /**
     * Check the badge show page functions.
     */
    public function testBadgeShow(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        /** @var Badge $badge */
        $badge = Badge::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get('/badges/'.$badge->slug);
        $response->assertStatus(200)
            ->assertViewHas('badge', $badge);
    }
}
