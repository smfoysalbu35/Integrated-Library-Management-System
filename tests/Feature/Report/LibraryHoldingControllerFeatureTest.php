<?php

namespace Tests\Feature\Report;

use App\Models\User;
use Tests\TestCase;

class LibraryHoldingControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_library_holding_report_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.library-holdings.index'))
            ->assertStatus(200)
            ->assertSee('Library Holding')
            ->assertSee('Library Holding Report');
    }

    public function test_it_throws_authorization_error_when_displaying_the_library_holding_report_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.library-holdings.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_library_holding_report_page_while_user_still_logged_out()
    {
        $this->get(route('report.library-holdings.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //print
    public function test_it_can_print_the_library_holding_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.library-holdings.print'))
            ->assertStatus(200)
            ->assertSee('Library Management System')
            ->assertSee('Library Holding Report');
    }

    public function test_it_throws_authorization_error_when_printing_the_library_holding_report()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.library-holdings.print'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_printing_the_library_holding_report_while_user_still_logged_out()
    {
        $this->get(route('report.library-holdings.print'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
