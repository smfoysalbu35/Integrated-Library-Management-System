<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_locations_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('locations.index'))
            ->assertStatus(200)
            ->assertSee('Manage Location');
    }

    public function test_it_throws_authorization_error_when_displaying_the_locations_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('locations.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_locations_list_page_while_user_still_logged_out()
    {
        $this->get(route('locations.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //get
    public function test_it_can_list_the_locations()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('locations.get'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_locations()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('locations.get'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_locations_while_user_still_logged_out()
    {
        $this->get(route('locations.get'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_locations()
    {
        $user = factory(User::class)->create();
        $location = factory(Location::class)->create();

        $this->actingAs($user)
            ->get(route('locations.search'), ['search' => $location->name])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_locations()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $location = factory(Location::class)->create();

        $this->actingAs($user)
            ->get(route('locations.search'), ['search' => $location->name])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_locations_while_user_still_logged_out()
    {
        $location = factory(Location::class)->create();

        $this->get(route('locations.search'), ['search' => $location->name])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Location Data
    public function location()
    {
        return [
            'name' => $this->faker->unique()->name,
            'symbol' => $this->faker->word,
            'allowed' => $this->faker->word,
        ];
    }

    //store
    public function test_it_can_create_the_location()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('locations.store'), $this->location())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_location()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('locations.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_location()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('locations.store'), $this->location())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_location_while_user_still_logged_out()
    {
        $this->post(route('locations.store'), $this->location())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_location()
    {
        $user = factory(User::class)->create();
        $location = factory(Location::class)->create();

        $this->actingAs($user)
            ->get(route('locations.show', ['location' => $location->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_location()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $location = factory(Location::class)->create();

        $this->actingAs($user)
            ->get(route('locations.show', ['location' => $location->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_location()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('locations.show', ['location' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_location_while_user_still_logged_out()
    {
        $location = factory(Location::class)->create();

        $this->get(route('locations.show', ['location' => $location->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_location()
    {
        $user = factory(User::class)->create();
        $location = factory(Location::class)->create();

        $this->actingAs($user)
            ->put(route('locations.update', ['location' => $location->id]), $this->location())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_location()
    {
        $user = factory(User::class)->create();
        $location = factory(Location::class)->create();

        $this->actingAs($user)
            ->put(route('locations.update', ['location' => $location->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_location()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $location = factory(Location::class)->create();

        $this->actingAs($user)
            ->put(route('locations.update', ['location' => $location->id]), $this->location())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_location()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('locations.update', ['location' => $this->faker->randomNumber(9)]), $this->location())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_location_while_user_still_logged_out()
    {
        $location = factory(Location::class)->create();

        $this->put(route('locations.update', ['location' => $location->id]), $this->location())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_location()
    {
        $user = factory(User::class)->create();
        $location = factory(Location::class)->create();

        $this->actingAs($user)
            ->delete(route('locations.destroy', ['location' => $location->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_location()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $location = factory(Location::class)->create();

        $this->actingAs($user)
            ->delete(route('locations.destroy', ['location' => $location->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_location()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('locations.destroy', ['location' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_location_while_user_still_logged_out()
    {
        $location = factory(Location::class)->create();

        $this->delete(route('locations.destroy', ['location' => $location->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
