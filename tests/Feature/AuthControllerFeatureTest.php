<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Lockout;
use Tests\TestCase;

class AuthControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_login_page()
    {
        $this->get(route('login.index'))
            ->assertStatus(200)
            ->assertSee('Login Form');
    }

    public function test_it_redirects_to_dashboard_page_when_user_is_already_logged_in()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('login.index'))
            ->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }

    //login
    public function test_it_can_login_the_user()
    {
        $user = factory(User::class)->create(['password' => Hash::make(config('global.default_password'))]);

        $this->post(route('login'), ['email' => $user->email, 'password' => config('global.default_password')])
            ->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }

    public function test_it_throws_validation_error_during_login()
    {
        $this->post(route('login'), [])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'))
            ->assertSessionHas(['errors']);
    }

    public function test_it_redirects_back_to_login_page_when_credentials_are_wrong()
    {
        $user = factory(User::class)->create();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'unknown'])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'))
            ->assertSessionHasErrors();
    }

    public function test_it_throws_the_too_many_login_attempts_event()
    {
        $this->expectsEvents(Lockout::class);

        $user = factory(User::class)->create();

        for($i = 0; $i <= 5; $i++)
            $this->post(route('login'), ['email' => $user->email, 'password' => 'unknown']);
    }

    //logout
    public function test_it_can_logout_the_user()
    {
        $user = factory(User::class)->create();
        factory(UserLog::class)->create([
            'user_id' => $user->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    public function test_it_redirects_to_login_page_during_logout_while_user_still_logged_out()
    {
        $this->post(route('logout'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
