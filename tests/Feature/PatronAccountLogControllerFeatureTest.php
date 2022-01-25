<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PatronAccountLogControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_patron_account_logs_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('patron-account-logs.index'))
            ->assertStatus(200)
            ->assertSee('Patron Account Logs Record')
            ->assertSee('Patron Account Logs List');
    }

    public function test_it_throws_authorization_error_when_displaying_the_patron_account_logs_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('patron-account-logs.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_patron_account_logs_list_page_while_user_still_logged_out()
    {
        $this->get(route('patron-account-logs.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
