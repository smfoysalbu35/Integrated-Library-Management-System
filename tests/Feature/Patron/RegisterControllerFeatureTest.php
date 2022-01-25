<?php

namespace Tests\Feature\Patron;

use App\Models\Patron;
use App\Models\PatronAccount;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_patron_account_registration_page()
    {
        $this->get(route('patron-web.register.index'))
            ->assertStatus(200)
            ->assertSee('Patron Account Registration Form');
    }

    public function test_it_redirects_to_home_page_when_patron_account_is_already_logged_in()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($patronAccount, 'patron')
            ->get(route('patron-web.register.index'))
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.home'));
    }

    //Patron Account Data
    public function patronAccount()
    {
        $patron = factory(Patron::class)->create();

        return [
            'patron_no' => $patron->patron_no,
            'email' => $this->faker->unique()->safeEmail,
            'password' => config('global.default_password'),
            'password_confirmation' => config('global.default_password'),
        ];
    }

    //register
    public function test_it_can_register_the_patron_account()
    {
        $this->post(route('patron-web.register'), $this->patronAccount())
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.home'));
    }

    public function test_it_throws_validation_error_during_patron_account_registration()
    {
        $this->post(route('patron-web.register'), [])
            ->assertStatus(302)
            ->assertSessionHas(['errors']);
    }

    public function test_it_errors_when_the_patron_no_is_already_registered()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $data = $this->patronAccount();
        $data['patron_no'] = $patronAccount->patron->patron_no;

        $this->post(route('patron-web.register'), $data)
            ->assertStatus(302)
            ->assertSessionHas(['error']);
    }

    public function test_it_errors_when_the_email_is_already_taken()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $data = $this->patronAccount();
        $data['email'] = $patronAccount->patron->email;

        $this->post(route('patron-web.register'), $data)
            ->assertStatus(302)
            ->assertSessionHas(['errors']);
    }

    public function test_it_errors_if_the_password_is_less_than_eight_characters()
    {
        $data = $this->patronAccount();
        $data['password'] = 'secret';
        $data['password_confirmation'] = 'secret';

        $this->post(route('patron-web.register'), $data)
            ->assertStatus(302)
            ->assertSessionHas(['errors']);
    }
}
