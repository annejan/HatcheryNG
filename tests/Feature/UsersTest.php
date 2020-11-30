<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Class UsersTest.
 *
 * @author annejan@badge.team
 */
class UsersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * login failed get redirected.
     */
    public function testLoginFail(): void
    {
        $response = $this
            ->withSession(['_token' => 'test'])
            ->post('/login', [
                'email'    => 'annejan@noprotocol.nl',
                'password' => 'badPass',
                '_token'   => 'test',
            ]);
        $response->assertStatus(302)
            ->assertRedirect('')
            ->assertSessionHasErrors()
            ->assertSessionHas('_old_input', [
                'email'  => 'annejan@noprotocol.nl',
                '_token' => 'test',
            ]);
    }

    /**
     * Login, go to home.
     */
    public function testLogin(): void
    {
        $password = $this->faker->password;
        $user = User::factory()->create(['password' => bcrypt($password)]);
        $response = $this
            ->withSession(['_token' => 'test'])
            ->post('/login', [
                'email'    => $user->email,
                'password' => $password,
                '_token'   => 'test',
            ]);
        $response->assertStatus(302)->assertRedirect('/dashboard');
    }

    /**
     * Check the logout functionality.
     */
    public function testLogout(): void
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->post('/logout');
        $response->assertStatus(302)
            ->assertRedirect('');
    }

    /**
     * Test a password change and login (full flow).
     */
    public function testUserResetPassword(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this
            ->withSession(['_token' => 'test'])
            ->post('/forgot-password', [
                'email'  => $user->email,
                '_token' => 'test',
            ]);
        $response->assertStatus(302)
            ->assertRedirect('/');

        $token = null;

        Notification::assertSentTo(
            [$user],
            ResetPassword::class,
            function ($notification, $channels) use (&$token) {
                $token = $notification->token;

                return true;
            }
        );

        $this->assertNotNull($token);

        $response = $this->get('/reset-password/'.$token);
        $response->assertStatus(200);

        $password = $this->faker->password(8, 20);

        $response = $this
            ->withSession(['_token' => 'test'])
            ->post('/reset-password', [
                'email'                 => $user->email,
                'token'                 => $token,
                'password'              => $password,
                'password_confirmation' => $password,
                '_token'                => 'test',
            ]);
        $response->assertStatus(302)->assertRedirect('/login');

        $response = $this
            ->withSession(['_token' => 'test'])
            ->post('/login', [
                'email'    => $user->email,
                'password' => $password,
                '_token'   => 'test',
            ]);
        $response->assertStatus(302)->assertRedirect('/dashboard');
    }

    /**
     * Register, go to /home . .
     */
    public function testRegister(): void
    {
        $password = $this->faker->password(8, 20);
        $email = $this->faker->email;
        $response = $this
            ->withSession(['_token' => 'test'])
            ->post('/register', [
                'name'                  => $this->faker->name,
                'email'                 => $email,
                'editor'                => 'default',
                'password'              => $password,
                'password_confirmation' => $password,
                '_token'                => 'test',
            ]);
        $response->assertStatus(302)->assertRedirect('/dashboard');
    }

//    /**
//     * Check if random user can not view Horizon page.
//     */
//    public function testUserViewHorizon(): void
//    {
//        $user = User::factory()->create();
//        $response = $this->actingAs($user)->get('/horizon/dashboard');
//        $response->assertStatus(403);
//    }

    /**
     * Check if admin user can view Horizon page.
     */
    public function testAdminUserViewHorizon(): void
    {
        $user = User::factory()->create();
        $user->admin = true;
        $user->save();
        $response = $this->actingAs($user)->get('/horizon/dashboard');
        $response->assertStatus(200);
    }
}
