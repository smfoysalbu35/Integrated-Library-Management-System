<?php

namespace Tests\Feature\Report;

use App\Models\User;
use Tests\TestCase;

class AuthorListControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_author_list_report_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.author-lists.index'))
            ->assertStatus(200)
            ->assertSee('Author List')
            ->assertSee('Author List Report');
    }

    public function test_it_throws_authorization_error_when_displaying_the_author_list_report_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.author-lists.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_author_list_report_page_while_user_still_logged_out()
    {
        $this->get(route('report.author-lists.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //print
    public function test_it_can_print_the_author_list_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.author-lists.print'))
            ->assertStatus(200)
            ->assertSee('Library Management System')
            ->assertSee('Author List Report');
    }

    public function test_it_throws_authorization_error_when_printing_the_author_list_report()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.author-lists.print'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_printing_the_author_list_report_while_user_still_logged_out()
    {
        $this->get(route('report.author-lists.print'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
