<?php

namespace Tests\Feature;

use App\Models\PatronAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatronAccountControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_patron_accounts_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('patron-accounts.index'))
            ->assertStatus(200)
            ->assertSee('Manage Patron Account')
            ->assertSee('Patron Account List');
    }

    public function test_it_throws_authorization_error_when_displaying_the_patron_accounts_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('patron-accounts.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_patron_accounts_list_page_while_user_still_logged_out()
    {
        $this->get(route('patron-accounts.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_patron_account()
    {
        $user = factory(User::class)->create();
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($user)
            ->delete(route('patron-accounts.destroy', ['patron_account' => $patronAccount->id]))
            ->assertStatus(302)
            ->assertRedirect(route('patron-accounts.index'))
            ->assertSessionHas(['message' => 'Patron account is successfully deleted.']);
    }

    public function test_it_throws_authorization_error_when_deleting_the_patron_account()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($user)
            ->delete(route('patron-accounts.destroy', ['patron_account' => $patronAccount->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_patron_account()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('patron-accounts.destroy', ['patron_account' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_patron_account_while_user_still_logged_out()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->delete(route('patron-accounts.destroy', ['patron_account' => $patronAccount->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
