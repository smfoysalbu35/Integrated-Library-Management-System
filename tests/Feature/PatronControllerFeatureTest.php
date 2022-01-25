<?php

namespace Tests\Feature;

use App\Models\Patron;
use App\Models\PatronType;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatronControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_patrons_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('patrons.index'))
            ->assertStatus(200)
            ->assertSee('Patron List');
    }

    public function test_it_throws_authorization_error_when_displaying_the_patrons_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('patrons.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_patrons_list_page_while_user_still_logged_out()
    {
        $this->get(route('patrons.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //create
    public function test_it_can_display_the_create_patron_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('patrons.create'))
            ->assertStatus(200)
            ->assertSee('Add Patron');
    }

    public function test_it_throws_authorization_error_when_displaying_the_create_patron_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('patrons.create'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_create_patron_page_while_user_still_logged_out()
    {
        $this->get(route('patrons.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Patron Data
    protected function patron()
    {
        $patronType = factory(PatronType::class)->create();
        $section = factory(Section::class)->create();

        return [
            'patron_no' => $this->faker->unique()->isbn10,
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName('male'),
            'middle_name' => $this->faker->lastName,

            'contact_no' => $this->faker->phoneNumber,
            'image' => $this->faker->imageUrl(640, 480),

            'house_no' => $this->faker->buildingNumber,
            'street' => $this->faker->streetName,
            'barangay' => $this->faker->secondaryAddress,
            'municipality' => $this->faker->city,
            'province' => $this->faker->state,

            'patron_type_id' => $patronType->id,
            'section_id' => $section->id,
        ];
    }

    //store
    public function test_it_can_create_the_patron()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('patrons.store'), $this->patron())
            ->assertStatus(302)
            ->assertRedirect(route('patrons.create'))
            ->assertSessionHas(['message' => 'Patron is successfully added.']);
    }

    public function test_it_throws_validation_error_when_creating_the_patron()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('patrons.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_patron()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('patrons.store'), $this->patron())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_patron_while_user_still_logged_out()
    {
        $this->post(route('patrons.store'), $this->patron())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //edit
    public function test_it_can_display_the_edit_patron_page()
    {
        $user = factory(User::class)->create();
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->get(route('patrons.edit', ['patron' => $patron->id]))
            ->assertStatus(200)
            ->assertSee('Edit Patron');
    }

    public function test_it_throws_authorization_error_when_displaying_the_edit_patron_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->get(route('patrons.edit', ['patron' => $patron->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_displaying_the_edit_patron_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('patrons.edit', ['patron' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_edit_patron_page_while_user_still_logged_out()
    {
        $patron = factory(Patron::class)->create();

        $this->get(route('patrons.edit', ['patron' => $patron->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_patron()
    {
        $user = factory(User::class)->create();
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->put(route('patrons.update', ['patron' => $patron->id]), $this->patron())
            ->assertStatus(302)
            ->assertRedirect(route('patrons.edit', ['patron' => $patron->id]))
            ->assertSessionHas(['message' => 'Patron is successfully updated.']);
    }

    public function test_it_throws_validation_error_when_updating_the_patron()
    {
        $user = factory(User::class)->create();
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->put(route('patrons.update', ['patron' => $patron->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_patron()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->put(route('patrons.update', ['patron' => $patron->id]), $this->patron())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_patron()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('patrons.update', ['patron' => $this->faker->randomNumber(9)]), $this->patron())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_patron_while_user_still_logged_out()
    {
        $patron = factory(Patron::class)->create();

        $this->put(route('patrons.update', ['patron' => $patron->id]), $this->patron())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_patron()
    {
        $user = factory(User::class)->create();
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->delete(route('patrons.destroy', ['patron' => $patron->id]))
            ->assertStatus(302)
            ->assertRedirect(route('patrons.index'))
            ->assertSessionHas(['message' => 'Patron is successfully deleted.']);
    }

    public function test_it_throws_authorization_error_when_deleting_the_patron()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->delete(route('patrons.destroy', ['patron' => $patron->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_patron()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('patrons.destroy', ['patron' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_patron_while_user_still_logged_out()
    {
        $patron = factory(Patron::class)->create();

        $this->delete(route('patrons.destroy', ['patron' => $patron->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
