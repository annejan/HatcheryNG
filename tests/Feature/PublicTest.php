<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Models\Category;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class PublicTest.
 *
 * @author annejan@badge.team
 */
class PublicTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function testWelcome(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Check redirect to /login when going to the /dashboard page.
     */
    public function testHomeRedirect(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * Check JSON request Unauthenticated . .
     */
    public function testJsonRedirect(): void
    {
        $response = $this->json('GET', '/dashboard');
        $response->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /**
     * Check JSON egg request . .
     */
    public function testProjectGetJsonModelNotFound(): void
    {
        $response = $this->json('GET', '/eggs/get/something/json');
        $response->assertStatus(404)
            ->assertExactJson(['message' => 'No releases found']);
    }

    /**
     * Check JSON egg request . .
     */
    public function testProjectGetJson(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();
        $category = $version->project->category()->first();

        $response = $this->json('GET', '/eggs/get/'.$version->project->slug.'/json');
        $response->assertStatus(200)
            ->assertExactJson([
                'name'        => $version->project->name,
                'description' => null,
                'info'        => ['version' => '1'],
                'category'    => $category->slug,
                'releases'    => [
                    '1' => [
                        [
                            'url' => url('some_path.tar.gz'),
                        ],
                    ],
                ],
                'min_firmware' => null,
                'max_firmware' => null,
            ]);
    }

    /**
     * Check JSON egg request . .
     */
    public function testProjectGetJsonDescription(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();
        $category = $version->project->category()->first();

        $response = $this->json('GET', '/eggs/get/'.$version->project->slug.'/json?description=true');
        $response->assertStatus(200)
            ->assertExactJson([
                'description' => null,
                'name'        => $version->project->name,
                'info'        => ['version' => '1'],
                'category'    => $category->slug,
                'releases'    => [
                    '1' => [
                        [
                            'url' => url('some_path.tar.gz'),
                        ],
                    ],
                ],
                'min_firmware' => null,
                'max_firmware' => null,
            ]);
    }

    /**
     * Check JSON egg request . .
     */
    public function testProjectGetJson404(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();

        $response = $this->json('GET', '/eggs/get/'.$version->project->slug.'/json');
        $response->assertStatus(404)
            ->assertExactJson(['message' => 'No releases found']);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testProjectListJson(): void
    {
        $response = $this->json('GET', '/eggs/list/json');
        $response->assertStatus(200)->assertExactJson([]);

        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();
        File::factory()->create(['version_id' => $version->id]);

        $category = $version->project->category()->first();

        $response = $this->json('GET', '/eggs/list/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'author'                  => $version->project->user->name,
                    'name'                    => $version->project->name,
                    'description'             => null,
                    'revision'                => '1',
                    'slug'                    => $version->project->slug,
                    'size_of_content'         => $version->project->size_of_content,
                    'size_of_zip'             => 0,
                    'category'                => $category->slug,
                    'download_counter'        => 0,
                    'status'                  => 'unknown',
                    'published_at'            => null,
                    'min_firmware'            => null,
                    'max_firmware'            => null,
                ],
            ]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testProjectSearchJson(): void
    {
        $response = $this->json('GET', '/eggs/search/something/json');
        $response->assertStatus(200)->assertExactJson([]);

        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();

        $len = strlen($version->project->name);
        $category = $version->project->category()->first();

        $response = $this->json('GET', '/eggs/search/'.substr($version->project->name, 2, $len - 4).'/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'author'                  => $version->project->user->name,
                    'name'                    => $version->project->name,
                    'description'             => null,
                    'revision'                => '1',
                    'slug'                    => $version->project->slug,
                    'size_of_content'         => 0,
                    'size_of_zip'             => 0,
                    'category'                => $category->slug,
                    'download_counter'        => 0,
                    'status'                  => 'unknown',
                    'published_at'            => null,
                    'min_firmware'            => null,
                    'max_firmware'            => null,
                ],
            ]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testProjectCategoryJson(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();

        $category = $version->project->category()->first();

        $response = $this->json('GET', '/eggs/category/nonexisting/json');
        $response->assertStatus(404);

        $response = $this->json('GET', '/eggs/category/'.$category->slug.'/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'author'                  => $version->project->user->name,
                    'name'                    => $version->project->name,
                    'description'             => null,
                    'revision'                => '1',
                    'slug'                    => $version->project->slug,
                    'size_of_content'         => 0,
                    'size_of_zip'             => 0,
                    'category'                => $category->slug,
                    'download_counter'        => 0,
                    'status'                  => 'unknown',
                    'published_at'            => null,
                    'min_firmware'            => null,
                    'max_firmware'            => null,
                ],
            ]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testCategoriesJson(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->project->save();
        $version->save();

        $category = $version->project->category()->first();

        $response = $this->json('GET', '/eggs/categories/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'eggs' => 1,
                ],
            ]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testCategoriesCountJson(): void
    {
        $user = User::factory()->create();

        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'iets anders';
        $version->project->save();
        $version->save();

        $category = $version->project->category()->first();

        $response = $this->json('GET', '/eggs/categories/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'eggs' => 1,
                ],
            ]);
    }

    /**
     * Check JSON eggs request . .
     */
    public function testCategoriesUnpublishedJson(): void
    {
        $user = User::factory()->create();

        $this->be($user);
        $version = Version::factory()->create();
        $version->project->save();
        $version->save();

        $category = $version->project->category()->first();

        $response = $this->json('GET', '/eggs/categories/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'eggs' => 0,
                ],
            ]);
    }

    /**
     * Check JSON basket request . .
     */
    public function testBasketListJson(): void
    {
        $badge = Badge::factory()->create();
        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();
        $version->project->badges()->attach($badge);
        File::factory()->create(['version_id' => $version->id]);
        $category = $version->project->category()->first();

        $response = $this->json('GET', '/basket/nonexisting/list/json');
        $response->assertStatus(404);

        $response = $this->json('GET', '/basket/'.$badge->slug.'/list/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'author'                  => $version->project->user->name,
                    'name'                    => $version->project->name,
                    'description'             => null,
                    'revision'                => '1',
                    'slug'                    => $version->project->slug,
                    'size_of_content'         => $version->project->size_of_content,
                    'size_of_zip'             => 0,
                    'category'                => $category->slug,
                    'download_counter'        => 0,
                    'status'                  => 'unknown',
                    'published_at'            => null,
                    'min_firmware'            => null,
                    'max_firmware'            => null,
                ],
            ]);
    }

    /**
     * Check JSON basket request . .
     */
    public function testBasketSearchJson(): void
    {
        $badge = Badge::factory()->create();
        $response = $this->json('GET', '/eggs/search/something/json');
        $response->assertStatus(200)->assertExactJson([]);

        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();
        $version->project->badges()->attach($badge);

        $len = strlen($version->project->name);
        $category = $version->project->category()->first();

        $response = $this->json('GET', '/basket/nonexisting/search/'.substr($version->project->name, 2, $len - 4).'/json');
        $response->assertStatus(404);

        $response = $this->json('GET', '/basket/'.$badge->slug.'/search/'.substr($version->project->name, 2, $len - 4).'/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'author'                  => $version->project->user->name,
                    'name'                    => $version->project->name,
                    'description'             => null,
                    'revision'                => '1',
                    'slug'                    => $version->project->slug,
                    'size_of_content'         => 0,
                    'size_of_zip'             => 0,
                    'category'                => $category->slug,
                    'download_counter'        => 0,
                    'status'                  => 'unknown',
                    'published_at'            => null,
                    'min_firmware'            => null,
                    'max_firmware'            => null,
                ],
            ]);
    }

    /**
     * Check JSON basket request . .
     */
    public function testBasketCategoriesJson(): void
    {
        $badge = Badge::factory()->create();
        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();
        $version->project->badges()->attach($badge);

        $category = $version->project->category()->first();

        $response = $this->json('GET', '/basket/nonexisting/categories/json');
        $response->assertStatus(404);

        $response = $this->json('GET', '/basket/'.$badge->slug.'/categories/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'eggs' => 1,
                ],
            ]);

        Category::factory()->create();

        $this->assertCount(2, Category::all());

        $response = $this->json('GET', '/basket/'.$badge->slug.'/categories/json');
        $response->assertExactJson([
            [
                'name' => $category->name,
                'slug' => $category->slug,
                'eggs' => 1,
            ],
        ]);
    }

    /**
     * Check JSON basket request . .
     */
    public function testBasketCategoryJson(): void
    {
        $badge = Badge::factory()->create();
        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();
        $version->project->badges()->attach($badge);

        $category = $version->project->category()->first();

        $response = $this->json('GET', '/basket/nonexisting/category/'.$category->slug.'/json');
        $response->assertStatus(404);

        $response = $this->json('GET', '/basket/'.$badge->slug.'/category/'.$category->slug.'/json');
        $response->assertStatus(200)
            ->assertExactJson([
                [
                    'author'           => $version->project->user->name,
                    'name'             => $version->project->name,
                    'description'      => null,
                    'revision'         => '1',
                    'slug'             => $version->project->slug,
                    'size_of_content'  => 0,
                    'size_of_zip'      => 0,
                    'category'         => $category->slug,
                    'download_counter' => 0,
                    'status'           => 'unknown',
                    'published_at'     => null,
                    'min_firmware'     => null,
                    'max_firmware'     => null,
                ],
            ]);
    }

    /**
     * Check JSON egg request . .
     */
    public function testProjectGetJsonMinMaxFirmware(): void
    {
        $user = User::factory()->create();
        $this->be($user);
        $version = Version::factory()->create();
        $version->zip = 'some_path.tar.gz';
        $version->save();
        $category = $version->project->category()->first();
        $version->project->min_firmware = 13;
        $version->project->max_firmware = 37;
        $version->project->save();

        $response = $this->json('GET', '/eggs/get/'.$version->project->slug.'/json');
        $response->assertStatus(200)
            ->assertExactJson([
                'name'        => $version->project->name,
                'description' => null,
                'info'        => ['version' => '1'],
                'category'    => $category->slug,
                'releases'    => [
                    '1' => [
                        [
                            'url' => url('some_path.tar.gz'),
                        ],
                    ],
                ],
                'min_firmware' => 13,
                'max_firmware' => 37,
            ]);
    }

    /**
     * Check redirect to /login when going to the /horizon page.
     */
    public function testHorizonRedirect(): void
    {
        $response = $this->get('/horizon');
        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * A basic test example.
     */
    public function testSwagger(): void
    {
        $response = $this->get('/api');
        $response->assertStatus(200);
    }
}
