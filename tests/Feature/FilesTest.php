<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class FilesTest.
 *
 * @author annejan@badge.team
 */
class FilesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check the files can be viewed (publicly).
     */
    public function testFilesView(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        $file = File::factory()->create();
        $response = $this
            ->call('get', '/files/'.$file->id);
        $response->assertStatus(200)->assertViewHas(['file']);
    }

    /**
     * Check the files can be downloaded (publicly).
     */
    public function testFilesDownload(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        $file = File::factory()->create();
        $response = $this
            ->call('get', '/download/'.$file->id);
        $response->assertStatus(200)->assertHeader('Content-Type', 'application/x-python-code');
    }
}
