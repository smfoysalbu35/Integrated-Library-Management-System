<?php

namespace Tests\Feature;

use App\Models\Accession;
use App\Models\Borrow;
use App\Models\Patron;
use App\Models\PatronAttendanceLog;
use App\Models\User;
use Tests\TestCase;

class ReturnBookControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_return_books_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('return-books.index'))
            ->assertStatus(200)
            ->assertSee('Return List');
    }

    public function test_it_throws_authorization_error_when_displaying_the_return_books_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('return-books.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_return_books_list_page_while_user_still_logged_out()
    {
        $this->get(route('return-books.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //create
    public function test_it_can_display_the_create_return_book_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('return-books.create'))
            ->assertStatus(200)
            ->assertSee('Return Book');
    }

    public function test_it_can_display_the_create_return_book_page_even_the_user_is_library_assistant()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('return-books.create'))
            ->assertStatus(200)
            ->assertSee('Return Book');
    }

    public function test_it_redirects_to_login_page_when_displaying_the_create_return_book_page_while_user_still_logged_out()
    {
        $this->get(route('return-books.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Return Book Data
    public function returnBook()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        return [
            'patron_no' => $patron->patron_no,
            'accession_no' => $accession->accession_no,
        ];
    }

    //store
    public function test_it_can_create_the_return_book()
    {
        $user = factory(User::class)->create();

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        factory(Borrow::class)->create([
            'patron_id' => $patron->id,
            'accession_id' => $accession->id,
        ]);

        $data = ['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no];

        $this->actingAs($user)
            ->post(route('return-books.store'), $data)
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_can_create_the_return_book_even_the_user_is_library_assistant()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        factory(Borrow::class)->create([
            'patron_id' => $patron->id,
            'accession_id' => $accession->id,
        ]);

        $data = ['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no];

        $this->actingAs($user)
            ->post(route('return-books.store'), $data)
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_during_creating_the_return_book()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('return-books.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_error_when_patron_not_login_in_patron_attendance_log_during_creating_the_return_book()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('return-books.store'), $this->returnBook())
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'patron-not-login'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_someone_borrow_the_book_during_creating_the_return_book()
    {
        $user = factory(User::class)->create();

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        factory(Borrow::class)->create([
            'patron_id' => factory(Patron::class)->create()->id,
            'accession_id' => $accession->id,
        ]);

        $data = ['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no];

        $this->actingAs($user)
            ->post(route('return-books.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'someone-borrow'])
            ->assertStatus(400);
    }

    public function test_it_redirects_to_login_page_when_creating_the_return_book_while_user_still_logged_out()
    {
        $this->post(route('return-books.store'), $this->returnBook())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
