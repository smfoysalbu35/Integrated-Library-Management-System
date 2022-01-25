<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserLogControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_user_logs_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('user-logs.index'))
            ->assertStatus(200)
            ->assertSee('User Logs Record')
            ->assertSee('User Logs List');
    }

    public function test_it_throws_authorization_error_when_displaying_the_user_logs_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('user-logs.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_user_logs_list_page_while_user_still_logged_out()
    {
        $this->get(route('user-logs.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
