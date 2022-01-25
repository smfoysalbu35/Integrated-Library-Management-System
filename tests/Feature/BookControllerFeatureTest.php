<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_display_the_books_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('books.index'))
            ->assertStatus(200)
            ->assertSee('Manage Book');
    }

    public function test_it_throws_authorization_error_when_displaying_the_books_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('books.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_books_list_page_while_user_still_logged_out()
    {
        $this->get(route('books.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //get
    public function test_it_can_list_the_books()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('books.get'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_books()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('books.get'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_books_while_user_still_logged_out()
    {
        $this->get(route('books.get'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_books()
    {
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->get(route('books.search'), ['search' => $book->title])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_books()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->get(route('books.search'), ['search' => $book->title])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_books_while_user_still_logged_out()
    {
        $book = factory(Book::class)->create();

        $this->get(route('books.search'), ['search' => $book->title])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Book Data
    public function book()
    {
        return [
            'title' => $this->faker->unique()->name,
            'call_number' => $this->faker->isbn10,
            'isbn' => $this->faker->isbn10,

            'edition' => $this->faker->word,
            'volume' => $this->faker->randomNumber(3),

            'publisher' => $this->faker->company,
            'place_publication' => $this->faker->city,

            'copy_right' => $this->faker->year,
            'copy' => $this->faker->randomDigitNot(0),
        ];
    }

    //store
    public function test_it_can_create_the_book()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('books.store'), $this->book())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_book()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('books.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_book()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('books.store'), $this->book())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_book_while_user_still_logged_out()
    {
        $this->post(route('books.store'), $this->book())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_book()
    {
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->get(route('books.show', ['book' => $book->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_book()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->get(route('books.show', ['book' => $book->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_book()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('books.show', ['book' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_book_while_user_still_logged_out()
    {
        $book = factory(Book::class)->create();

        $this->get(route('books.show', ['book' => $book->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_book()
    {
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->put(route('books.update', ['book' => $book->id]), $this->book())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_book()
    {
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->put(route('books.update', ['book' => $book->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_book()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->put(route('books.update', ['book' => $book->id]), $this->book())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_book()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('books.update', ['book' => $this->faker->randomNumber(9)]), $this->book())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_book_while_user_still_logged_out()
    {
        $book = factory(Book::class)->create();

        $this->put(route('books.update', ['book' => $book->id]), $this->book())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_book()
    {
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->delete(route('books.destroy', ['book' => $book->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_book()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $book = factory(Book::class)->create();

        $this->actingAs($user)
            ->delete(route('books.destroy', ['book' => $book->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_book()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('books.destroy', ['book' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_book_while_user_still_logged_out()
    {
        $book = factory(Book::class)->create();

        $this->delete(route('books.destroy', ['book' => $book->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
