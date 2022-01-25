<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class DashboardControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_dashboard_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertSee('Dashboard')
            ->assertSee('No. Of User')
            ->assertSee('No. Of Patron')
            ->assertSee('No. Of Book')
            ->assertSee('No. Of Accession')
            ->assertSee('Latest Borrow Transaction')
            ->assertSee('Latest Return Book Transaction')
            ->assertSee('Latest Reservation Transaction')
            ->assertSee('Latest Payment Transaction');
    }

    public function test_it_redirects_to_login_page_when_displaying_the_dashboard_page_while_user_still_logged_out()
    {
        $this->get(route('dashboard'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
