<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_registration_page()
    {
        $this->get(route('register.index'))
            ->assertStatus(200)
            ->assertSee('Registration Form');
    }

    public function test_it_redirects_to_dashboard_page_when_user_is_already_logged_in()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('register.index'))
            ->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }

    //User Data
    protected function user()
    {
        return [
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName('male'),
            'middle_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => config('global.default_password'),
            'password_confirmation' => config('global.default_password'),
        ];
    }

    //register
    public function test_it_can_register_the_user()
    {
        $this->post(route('register'), $this->user())
            ->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }

    public function test_it_throws_validation_error_during_registration()
    {
        $this->post(route('register'), [])
            ->assertStatus(302)
            ->assertSessionHas(['errors']);
    }

    public function test_it_errors_when_the_email_is_already_taken()
    {
        $user = factory(User::class)->create();
        $data = $this->user();
        $data['email'] = $user->email;

        $this->post(route('register'), $data)
            ->assertStatus(302)
            ->assertSessionHas(['errors']);
    }

    public function test_it_errors_if_the_password_is_less_than_eight_characters()
    {
        $data = $this->user();
        $data['password'] = 'secret';
        $data['password_confirmation'] = 'secret';

        $this->post(route('register'), $data)
            ->assertStatus(302)
            ->assertSessionHas(['errors']);
    }
}
