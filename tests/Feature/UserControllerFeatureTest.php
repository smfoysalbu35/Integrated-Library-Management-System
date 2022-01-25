<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_users_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertStatus(200)
            ->assertSee('User List');
    }

    public function test_it_throws_authorization_error_when_displaying_the_users_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_users_list_page_while_user_still_logged_out()
    {
        $this->get(route('users.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //create
    public function test_it_can_display_the_create_user_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('users.create'))
            ->assertStatus(200)
            ->assertSee('Add User');
    }

    public function test_it_throws_authorization_error_when_displaying_the_create_user_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('users.create'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_create_user_page_while_user_still_logged_out()
    {
        $this->get(route('users.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //User Data
    public function user()
    {
        return [
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName('male'),
            'middle_name' => $this->faker->lastName,

            'email' => $this->faker->unique()->safeEmail,
            'image' => UploadedFile::fake()->image('image.jpg', 640, 480)->size(100),
            'user_type' => 1,
            'status' => 1,
        ];
    }

    //store
    public function test_it_can_create_the_user()
    {
        $user = factory(User::class)->create();
        $data = $this->user();
        $data['password'] = config('global.default_password');
        $data['password_confirmation'] = config('global.default_password');

        $this->actingAs($user)
            ->post(route('users.store'), $data)
            ->assertStatus(302)
            ->assertRedirect(route('users.create'))
            ->assertSessionHas(['message' => 'User is successfully added.']);
    }

    public function test_it_throws_validation_error_when_creating_the_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('users.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_user()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('users.store'), $this->user())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_user_while_user_still_logged_out()
    {
        $this->post(route('users.store'), $this->user())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //edit
    public function test_it_can_display_the_edit_user_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('users.edit', ['user' => $user->id]))
            ->assertStatus(200)
            ->assertSee('Edit User');
    }

    public function test_it_throws_authorization_error_when_displaying_the_edit_user_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $otherUser = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('users.edit', ['user' => $otherUser->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_displaying_the_edit_user_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('users.edit', ['user' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_edit_user_page_while_user_still_logged_out()
    {
        $user = factory(User::class)->create();

        $this->get(route('users.edit', ['user' => $user->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_user()
    {
        $user = factory(User::class)->create(['password' => Hash::make(config('global.default_password'))]);
        $data = $this->user();
        $data['old_password'] = config('global.default_password');
        $data['new_password'] = 'password';
        $data['new_password_confirmation'] = 'password';

        $this->actingAs($user)
            ->put(route('users.update', ['user' => $user->id]), $data)
            ->assertStatus(302)
            ->assertRedirect(route('users.edit', ['user' => $user->id]))
            ->assertSessionHas(['message' => 'User is successfully updated.']);
    }

    public function test_it_throws_validation_error_when_old_password_is_wrong_while_updating_the_user()
    {
        $user = factory(User::class)->create(['password' => Hash::make(config('global.default_password'))]);
        $data = $this->user();
        $data['old_password'] = 'unknown';
        $data['new_password'] = 'password';
        $data['new_password_confirmation'] = 'password';

        $this->actingAs($user)
            ->put(route('users.update', ['user' => $user->id]), $data)
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_validation_error_when_updating_the_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('users.update', ['user' => $user->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_user()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $otherUser = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('users.update', ['user' => $otherUser->id]), $this->user())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('users.update', ['user' => $this->faker->randomNumber(9)]), $this->user())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_user_while_user_still_logged_out()
    {
        $user = factory(User::class)->create();

        $this->put(route('users.update', ['user' => $user->id]), $this->user())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_user()
    {
        $authUser = factory(User::class)->create();
        $dummyUser = factory(User::class)->create();

        $this->actingAs($authUser)
            ->delete(route('users.destroy', ['user' => $dummyUser->id]))
            ->assertStatus(302)
            ->assertRedirect(route('users.index'))
            ->assertSessionHas(['message' => 'User is successfully deleted.']);
    }

    public function test_it_throws_authorization_error_when_deleting_the_user()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $dummyUser = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('users.destroy', ['user' => $dummyUser->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('users.destroy', ['user' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_user_while_user_still_logged_out()
    {
        $user = factory(User::class)->create();

        $this->delete(route('users.destroy', ['user' => $user->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
