<?php

namespace Tests\Feature;

use App\Models\PatronType;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatronTypeControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_patron_types_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('patron-types.index'))
            ->assertStatus(200)
            ->assertSee('Manage Patron Type');
    }

    public function test_it_throws_authorization_error_when_displaying_the_patron_types_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('patron-types.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_patron_types_list_page_while_user_still_logged_out()
    {
        $this->get(route('patron-types.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //get
    public function test_it_can_list_the_patron_types()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('patron-types.get'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_patron_types()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('patron-types.get'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_patron_types_while_user_still_logged_out()
    {
        $this->get(route('patron-types.get'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_patron_types()
    {
        $user = factory(User::class)->create();
        $patronType = factory(PatronType::class)->create();

        $this->actingAs($user)
            ->get(route('patron-types.search'), ['search' => $patronType->name])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_patron_types()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patronType = factory(PatronType::class)->create();

        $this->actingAs($user)
            ->get(route('patron-types.search'), ['search' => $patronType->name])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_patron_types_while_user_still_logged_out()
    {
        $patronType = factory(PatronType::class)->create();

        $this->get(route('patron-types.search'), ['search' => $patronType->name])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Patron Type Data
    public function patronType()
    {
        return [
            'name' => $this->faker->unique()->name,
            'fines' => $this->faker->randomDigitNot(0),
            'no_of_borrow_allowed' => $this->faker->randomDigitNot(0),
            'no_of_day_borrow_allowed' => $this->faker->randomDigitNot(0),
            'no_of_reserve_allowed' => $this->faker->randomDigitNot(0),
            'no_of_day_reserve_allowed' => $this->faker->randomDigitNot(0),
        ];
    }

    //store
    public function test_it_can_create_the_patron_type()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('patron-types.store'), $this->patronType())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_patron_type()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('patron-types.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_patron_type()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('patron-types.store'), $this->patronType())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_patron_type_while_user_still_logged_out()
    {
        $this->post(route('patron-types.store'), $this->patronType())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_patron_type()
    {
        $user = factory(User::class)->create();
        $patronType = factory(PatronType::class)->create();

        $this->actingAs($user)
            ->get(route('patron-types.show', ['patron_type' => $patronType->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_patron_type()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patronType = factory(PatronType::class)->create();

        $this->actingAs($user)
            ->get(route('patron-types.show', ['patron_type' => $patronType->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_patron_type()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('patron-types.show', ['patron_type' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_patron_type_while_user_still_logged_out()
    {
        $patronType = factory(PatronType::class)->create();

        $this->get(route('patron-types.show', ['patron_type' => $patronType->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_patron_type()
    {
        $user = factory(User::class)->create();
        $patronType = factory(PatronType::class)->create();

        $this->actingAs($user)
            ->put(route('patron-types.update', ['patron_type' => $patronType->id]), $this->patronType())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_patron_type()
    {
        $user = factory(User::class)->create();
        $patronType = factory(PatronType::class)->create();

        $this->actingAs($user)
            ->put(route('patron-types.update', ['patron_type' => $patronType->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_patron_type()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patronType = factory(PatronType::class)->create();

        $this->actingAs($user)
            ->put(route('patron-types.update', ['patron_type' => $patronType->id]), $this->patronType())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_patron_type()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('patron-types.update', ['patron_type' => $this->faker->randomNumber(9)]), $this->patronType())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_patron_type_while_user_still_logged_out()
    {
        $patronType = factory(PatronType::class)->create();

        $this->put(route('patron-types.update', ['patron_type' => $patronType->id]), $this->patronType())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_patron_type()
    {
        $user = factory(User::class)->create();
        $patronType = factory(PatronType::class)->create();

        $this->actingAs($user)
            ->delete(route('patron-types.destroy', ['patron_type' => $patronType->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_patron_type()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patronType = factory(PatronType::class)->create();

        $this->actingAs($user)
            ->delete(route('patron-types.destroy', ['patron_type' => $patronType->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_patron_type()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('patron-types.destroy', ['patron_type' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_patron_type_while_user_still_logged_out()
    {
        $patronType = factory(PatronType::class)->create();

        $this->delete(route('patron-types.destroy', ['patron_type' => $patronType->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
