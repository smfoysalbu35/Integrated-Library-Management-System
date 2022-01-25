<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Subject;
use App\Models\BookSubject;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookSubjectControllerFeatureTest extends TestCase
{
    use WithFaker;

    //index
    public function test_it_can_list_the_book_subjects()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('book-subjects.index'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_listing_the_book_subjects()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('book-subjects.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_listing_the_book_subjects_while_user_still_logged_out()
    {
        $this->get(route('book-subjects.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //search
    public function test_it_can_search_the_book_subjects()
    {
        $user = factory(User::class)->create();
        $bookSubject = factory(BookSubject::class)->create();

        $this->actingAs($user)
            ->get(route('book-subjects.search'), ['search' => $bookSubject->subject->name])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_searching_the_book_subjects()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $bookSubject = factory(BookSubject::class)->create();

        $this->actingAs($user)
            ->get(route('book-subjects.search'), ['search' => $bookSubject->subject->name])
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_searching_the_book_subjects_while_user_still_logged_out()
    {
        $bookSubject = factory(BookSubject::class)->create();

        $this->get(route('book-subjects.search'), ['search' => $bookSubject->subject->name])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Book Subject Data
    public function bookSubject()
    {
        $book = factory(Book::class)->create();
        $subject = factory(Subject::class)->create();

        return [
            'book_id' => $book->id,
            'subject_id' => $subject->id,
        ];
    }

    //store
    public function test_it_can_create_the_book_subject()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('book-subjects.store'), $this->bookSubject())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_when_creating_the_book_subject()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('book-subjects.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_creating_the_book_subject()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->post(route('book-subjects.store'), $this->bookSubject())
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_creating_the_book_subject_while_user_still_logged_out()
    {
        $this->post(route('book-subjects.store'), $this->bookSubject())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //show
    public function test_it_can_show_the_book_subject()
    {
        $user = factory(User::class)->create();
        $bookSubject = factory(BookSubject::class)->create();

        $this->actingAs($user)
            ->get(route('book-subjects.show', ['book_subject' => $bookSubject->id]))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_showing_the_book_subject()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $bookSubject = factory(BookSubject::class)->create();

        $this->actingAs($user)
            ->get(route('book-subjects.show', ['book_subject' => $bookSubject->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_showing_the_book_subject()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('book-subjects.show', ['book_subject' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_showing_the_book_subject_while_user_still_logged_out()
    {
        $bookSubject = factory(BookSubject::class)->create();

        $this->get(route('book-subjects.show', ['book_subject' => $bookSubject->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //update
    public function test_it_can_update_the_book_subject()
    {
        $user = factory(User::class)->create();
        $bookSubject = factory(BookSubject::class)->create();

        $this->actingAs($user)
            ->put(route('book-subjects.update', ['book_subject' => $bookSubject->id]), $this->bookSubject())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_updating_the_book_subject()
    {
        $user = factory(User::class)->create();
        $bookSubject = factory(BookSubject::class)->create();

        $this->actingAs($user)
            ->put(route('book-subjects.update', ['book_subject' => $bookSubject->id]), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_authorization_error_when_updating_the_book_subject()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $bookSubject = factory(BookSubject::class)->create();

        $this->actingAs($user)
            ->put(route('book-subjects.update', ['book_subject' => $bookSubject->id]), $this->bookSubject())
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_updating_the_book_subject()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->put(route('book-subjects.update', ['book_subject' => $this->faker->randomNumber(9)]), $this->bookSubject())
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_updating_the_book_subject_while_user_still_logged_out()
    {
        $bookSubject = factory(BookSubject::class)->create();

        $this->put(route('book-subjects.update', ['book_subject' => $bookSubject->id]), $this->bookSubject())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //destroy
    public function test_it_can_delete_the_book_subject()
    {
        $user = factory(User::class)->create();
        $bookSubject = factory(BookSubject::class)->create();

        $this->actingAs($user)
            ->delete(route('book-subjects.destroy', ['book_subject' => $bookSubject->id]))
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(200);
    }

    public function test_it_throws_authorization_error_when_deleting_the_book_subject()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $bookSubject = factory(BookSubject::class)->create();

        $this->actingAs($user)
            ->delete(route('book-subjects.destroy', ['book_subject' => $bookSubject->id]))
            ->assertStatus(403);
    }

    public function test_it_throws_model_not_found_error_when_deleting_the_book_subject()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->delete(route('book-subjects.destroy', ['book_subject' => $this->faker->randomNumber(9)]))
            ->assertStatus(404);
    }

    public function test_it_redirects_to_login_page_when_deleting_the_book_subject_while_user_still_logged_out()
    {
        $bookSubject = factory(BookSubject::class)->create();

        $this->delete(route('book-subjects.destroy', ['book_subject' => $bookSubject->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
