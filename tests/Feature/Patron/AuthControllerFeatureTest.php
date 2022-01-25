<?php

namespace Tests\Feature\Patron;

use App\Models\PatronAccount;
use App\Models\PatronAccountLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Lockout;
use Tests\TestCase;

class AuthControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_patron_account_login_page()
    {
        $this->get(route('patron-web.login.index'))
            ->assertStatus(200)
            ->assertSee('Patron Account Login Form');
    }

    public function test_it_redirects_to_home_page_when_patron_account_is_already_logged_in()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($patronAccount, 'patron')
            ->get(route('patron-web.login.index'))
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.home'));
    }

    //login
    public function test_it_can_login_the_patron_account()
    {
        $patronAccount = factory(PatronAccount::class)->create(['password' => Hash::make(config('global.default_password'))]);

        $this->post(route('patron-web.login'), ['email' => $patronAccount->email, 'password' => config('global.default_password')])
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.home'));
    }

    public function test_it_throws_validation_error_during_patron_account_login()
    {
        $this->post(route('patron-web.login'), [])
            ->assertStatus(302)
            ->assertSessionHas(['errors']);
    }

    public function test_it_redirects_back_to_patron_account_login_page_when_credentials_are_wrong()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->post(route('patron-web.login'), ['email' => $patronAccount->email, 'password' => 'unknown'])
            ->assertStatus(302)
            ->assertSessionHasErrors();
    }

    public function test_it_throws_the_too_many_login_attempts_event()
    {
        $this->expectsEvents(Lockout::class);

        $patronAccount = factory(PatronAccount::class)->create();

        for($i = 0; $i <= 5; $i++)
            $this->post(route('patron-web.login'), ['email' => $patronAccount->email, 'password' => 'unknown']);
    }

    //logout
    public function test_it_can_logout_the_patron_account()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        factory(PatronAccountLog::class)->create([
            'patron_account_id' => $patronAccount->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $this->actingAs($patronAccount, 'patron')
            ->post(route('patron-web.logout'))
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.login.index'));
    }

    public function test_it_redirects_to_patron_account_login_page_during_patron_account_logout_while_patron_account_still_logged_out()
    {
        $this->post(route('patron-web.logout'))
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.login.index'));
    }
}
