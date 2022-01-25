<?php

namespace Tests\Feature;

use App\Models\CloseDate;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CloseDateControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_close_dates_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('close-dates.index'))
            ->assertStatus(200)
            ->assertSee('Manage Close Date');
    }

    public function test_it_throws_authorization_error_when_displaying_the_close_dates_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('close-dates.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_close_dates_list_page_while_user_still_logged_out()
    {
        $this->get(route('close-dates.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //get
    public function test_it_can_list_the_close_dates()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('close-dates.get'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_close_dates()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('close-dates.get'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_close_dates_while_user_still_logged_out()
    {
        $this->get(route('close-dates.get'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_close_dates()
    {
        $user = factory(User::class)->create();
        $closeDate = factory(CloseDate::class)->create();

        $this->actingAs($user)
            ->get(route('close-dates.search'), ['search' => $closeDate->close_date])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_close_dates()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $closeDate = factory(CloseDate::class)->create();

        $this->actingAs($user)
            ->get(route('close-dates.search'), ['search' => $closeDate->close_date])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_close_dates_while_user_still_logged_out()
    {
        $closeDate = factory(CloseDate::class)->create();

        $this->get(route('close-dates.search'), ['search' => $closeDate->close_date])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Close Date Data
    public function closeDate()
    {
        return [
            'close_date' => $this->faker->unique()->date,
            'description' => $this->faker->text(191),
        ];
    }

    //store
    public function test_it_can_create_the_close_date()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('close-dates.store'), $this->closeDate())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_close_date()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('close-dates.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_close_date()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('close-dates.store'), $this->closeDate())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_close_date_while_user_still_logged_out()
    {
        $this->post(route('close-dates.store'), $this->closeDate())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_close_date()
    {
        $user = factory(User::class)->create();
        $closeDate = factory(CloseDate::class)->create();

        $this->actingAs($user)
            ->get(route('close-dates.show', ['close_date' => $closeDate->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_close_date()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $closeDate = factory(CloseDate::class)->create();

        $this->actingAs($user)
            ->get(route('close-dates.show', ['close_date' => $closeDate->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_close_date()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('close-dates.show', ['close_date' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_close_date_while_user_still_logged_out()
    {
        $closeDate = factory(CloseDate::class)->create();

        $this->get(route('close-dates.show', ['close_date' => $closeDate->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_close_date()
    {
        $user = factory(User::class)->create();
        $closeDate = factory(CloseDate::class)->create();

        $this->actingAs($user)
            ->put(route('close-dates.update', ['close_date' => $closeDate->id]), $this->closeDate())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_close_date()
    {
        $user = factory(User::class)->create();
        $closeDate = factory(CloseDate::class)->create();

        $this->actingAs($user)
            ->put(route('close-dates.update', ['close_date' => $closeDate->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_close_date()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $closeDate = factory(CloseDate::class)->create();

        $this->actingAs($user)
            ->put(route('close-dates.update', ['close_date' => $closeDate->id]), $this->closeDate())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_close_date()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('close-dates.update', ['close_date' => $this->faker->randomNumber(9)]), $this->closeDate())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_close_date_while_user_still_logged_out()
    {
        $closeDate = factory(CloseDate::class)->create();

        $this->put(route('close-dates.update', ['close_date' => $closeDate->id]), $this->closeDate())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_close_date()
    {
        $user = factory(User::class)->create();
        $closeDate = factory(CloseDate::class)->create();

        $this->actingAs($user)
            ->delete(route('close-dates.destroy', ['close_date' => $closeDate->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_close_date()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $closeDate = factory(CloseDate::class)->create();

        $this->actingAs($user)
            ->delete(route('close-dates.destroy', ['close_date' => $closeDate->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_close_date()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('close-dates.destroy', ['close_date' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_close_date_while_user_still_logged_out()
    {
        $closeDate = factory(CloseDate::class)->create();

        $this->delete(route('close-dates.destroy', ['close_date' => $closeDate->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
