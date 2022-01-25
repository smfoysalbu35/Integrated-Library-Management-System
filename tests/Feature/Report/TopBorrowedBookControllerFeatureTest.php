<?php

namespace Tests\Feature\Report;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TopBorrowedBookControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_top_borrowed_book_report_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.top-borrowed-books.index'))
            ->assertStatus(200)
            ->assertSee('Top Borrowed Book')
            ->assertSee('Top Borrowed Book Report');
    }

    public function test_it_throws_authorization_error_when_displaying_the_top_borrowed_book_report_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.top-borrowed-books.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_top_borrowed_book_report_page_while_user_still_logged_out()
    {
        $this->get(route('report.top-borrowed-books.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Report Data
    public function data()
    {
        return [
            'from' => $this->faker->date,
            'to' => $this->faker->date,
        ];
    }

    //store
    public function test_it_can_list_the_top_borrowed_book_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('report.top-borrowed-books.store'), $this->data())
            ->assertJsonStructure(['from', 'to', 'topBorrowedBooks'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_listing_the_top_borrowed_book_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('report.top-borrowed-books.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_listing_the_top_borrowed_book_report()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('report.top-borrowed-books.store'), $this->data())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_top_borrowed_book_report_while_user_still_logged_out()
    {
        $this->post(route('report.top-borrowed-books.store'), $this->data())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //print
    public function test_it_can_print_the_top_borrowed_book_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.top-borrowed-books.print', $this->data()))
            ->assertStatus(200)
            ->assertSee('Library Management System')
            ->assertSee('Top Borrowed Book Report');
    }

    public function test_it_throws_validation_error_when_printing_the_top_borrowed_book_report()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('report.top-borrowed-books.print', []))
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_printing_the_top_borrowed_book_report()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('report.top-borrowed-books.print', $this->data()))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_printing_the_top_borrowed_book_report_while_user_still_logged_out()
    {
        $this->get(route('report.top-borrowed-books.print', $this->data()))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
