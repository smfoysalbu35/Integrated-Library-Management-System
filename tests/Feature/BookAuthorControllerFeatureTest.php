<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookAuthor;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookAuthorControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_list_the_book_authors()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('book-authors.index'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_book_authors()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('book-authors.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_book_authors_while_user_still_logged_out()
    {
        $this->get(route('book-authors.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_book_authors()
    {
        $user = factory(User::class)->create();
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->actingAs($user)
            ->get(route('book-authors.search'), ['search' => $bookAuthor->author->name])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_book_authors()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->actingAs($user)
            ->get(route('book-authors.search'), ['search' => $bookAuthor->author->name])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_book_authors_while_user_still_logged_out()
    {
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->get(route('book-authors.search'), ['search' => $bookAuthor->author->name])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Book Author Data
    public function bookAuthor()
    {
        $author = factory(Author::class)->create();
        $book = factory(Book::class)->create();

        return [
            'author_id' => $author->id,
            'book_id' => $book->id,
        ];
    }

    //store
    public function test_it_can_create_the_book_author()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('book-authors.store'), $this->bookAuthor())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_book_author()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('book-authors.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_book_author()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('book-authors.store'), $this->bookAuthor())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_book_author_while_user_still_logged_out()
    {
        $this->post(route('book-authors.store'), $this->bookAuthor())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_book_author()
    {
        $user = factory(User::class)->create();
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->actingAs($user)
            ->get(route('book-authors.show', ['book_author' => $bookAuthor->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_book_author()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->actingAs($user)
            ->get(route('book-authors.show', ['book_author' => $bookAuthor->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_book_author()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('book-authors.show', ['book_author' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_book_author_while_user_still_logged_out()
    {
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->get(route('book-authors.show', ['book_author' => $bookAuthor->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_book_author()
    {
        $user = factory(User::class)->create();
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->actingAs($user)
            ->put(route('book-authors.update', ['book_author' => $bookAuthor->id]), $this->bookAuthor())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_book_author()
    {
        $user = factory(User::class)->create();
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->actingAs($user)
            ->put(route('book-authors.update', ['book_author' => $bookAuthor->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_book_author()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->actingAs($user)
            ->put(route('book-authors.update', ['book_author' => $bookAuthor->id]), $this->bookAuthor())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_book_author()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('book-authors.update', ['book_author' => $this->faker->randomNumber(9)]), $this->bookAuthor())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_book_author_while_user_still_logged_out()
    {
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->put(route('book-authors.update', ['book_author' => $bookAuthor->id]), $this->bookAuthor())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_book_author()
    {
        $user = factory(User::class)->create();
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->actingAs($user)
            ->delete(route('book-authors.destroy', ['book_author' => $bookAuthor->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_book_author()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->actingAs($user)
            ->delete(route('book-authors.destroy', ['book_author' => $bookAuthor->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_book_author()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('book-authors.destroy', ['book_author' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_book_author_while_user_still_logged_out()
    {
        $bookAuthor = factory(BookAuthor::class)->create();

        $this->delete(route('book-authors.destroy', ['book_author' => $bookAuthor->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
