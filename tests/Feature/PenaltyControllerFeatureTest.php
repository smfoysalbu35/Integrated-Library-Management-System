<?php

namespace Tests\Feature;

use App\Models\Patron;
use App\Models\User;
use Tests\TestCase;

class PenaltyControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_penalties_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('penalties.index'))
            ->assertStatus(200)
            ->assertSee('Penalty Record')
            ->assertSee('Penalty List');
    }

    public function test_it_throws_authorization_error_when_displaying_the_penalties_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('penalties.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_penalties_list_page_while_user_still_logged_out()
    {
        $this->get(route('penalties.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Get Patron Penalty Record
    public function test_it_can_get_the_patron_penalty_record()
    {
        $user = factory(User::class)->create();
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->post(route('penalties.patron-record'), ['patron_no' => $patron->patron_no])
            ->assertJsonStructure(['penalties', 'total_penalty'])
            ->assertStatus(200);
    }

    public function test_it_can_get_the_patron_penalty_record_even_the_user_is_library_assistant()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->post(route('penalties.patron-record'), ['patron_no' => $patron->patron_no])
            ->assertJsonStructure(['penalties', 'total_penalty'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_getting_the_patron_penalty_record()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('penalties.patron-record'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_redirects_to_login_page_when_getting_the_patron_penalty_record_while_user_still_logged_out()
    {
        $patron = factory(Patron::class)->create();

        $this->post(route('penalties.patron-record'), ['patron_no' => $patron->patron_no])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
