<?php

namespace Tests\Feature;

use App\Models\Accession;
use App\Models\Borrow;
use App\Models\Patron;
use App\Models\PatronAttendanceLog;
use App\Models\Penalty;
use App\Models\ReturnBook;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class BorrowControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_borrows_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('borrows.index'))
            ->assertStatus(200)
            ->assertSee('Borrow List');
    }

    public function test_it_throws_authorization_error_when_displaying_the_borrows_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('borrows.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_borrows_list_page_while_user_still_logged_out()
    {
        $this->get(route('borrows.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //create
    public function test_it_can_display_the_create_borrow_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('borrows.create'))
            ->assertStatus(200)
            ->assertSee('Borrow Book');
    }

    public function test_it_can_display_the_create_borrow_page_even_the_user_is_library_assistant()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('borrows.create'))
            ->assertStatus(200)
            ->assertSee('Borrow Book');
    }

    public function test_it_redirects_to_login_page_when_displaying_the_create_borrow_page_while_user_still_logged_out()
    {
        $this->get(route('borrows.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Borrow Data
    public function borrow()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        return [
            'patron_no' => $patron->patron_no,
            'accession_no' => $accession->accession_no,
        ];
    }

    //store
    public function test_it_can_create_the_borrow()
    {
        $user = factory(User::class)->create();

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $data = ['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no];

        $this->actingAs($user)
            ->post(route('borrows.store'), $data)
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_can_create_the_borrow_even_the_user_is_library_assistant()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $data = ['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no];

        $this->actingAs($user)
            ->post(route('borrows.store'), $data)
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_during_creating_the_borrow()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('borrows.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_error_when_patron_not_login_in_patron_attendance_log_during_creating_the_borrow()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('borrows.store'), $this->borrow())
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'patron-not-login'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_has_penalty_during_creating_the_borrow()
    {
        $user = factory(User::class)->create();

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $borrow = factory(Borrow::class)->create([
            'patron_id' => $patron->id,
            'accession_id' => factory(Accession::class)->create()->id,
        ]);

        $returnBook = factory(ReturnBook::class)->create([
            'borrow_id' => $borrow->id,
            'patron_id' => $borrow->patron->id,
            'accession_id' => $borrow->accession->id,
        ]);

        factory(Penalty::class)->create([
            'return_book_id' => $returnBook->id,
            'patron_id' => $returnBook->patron->id,
            'accession_id' => $returnBook->accession->id,
        ]);

        $data = ['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no];

        $this->actingAs($user)
            ->post(route('borrows.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'penalty'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_exceed_the_maximum_no_of_borrow_allowed_during_creating_the_borrow()
    {
        $user = factory(User::class)->create();

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        factory(Borrow::class, $patron->patron_type->no_of_borrow_allowed)->create([
            'patron_id' => $patron->id,
            'accession_id' => factory(Accession::class)->create()->id,
        ]);

        $data = ['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no];

        $this->actingAs($user)
            ->post(route('borrows.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'maximum-borrow-exceed'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_already_borrow_the_book_during_creating_the_borrow()
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
            ->post(route('borrows.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'already-borrow'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_someone_borrow_the_book_during_creating_the_borrow()
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
            ->post(route('borrows.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'someone-borrow'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_someone_reserve_the_book_during_creating_the_borrow()
    {
        $user = factory(User::class)->create();

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $otherPatron = factory(Patron::class)->create();
        factory(Reservation::class)->create([
            'patron_id' => $otherPatron->id,
            'accession_id' => $accession->id,
            'reservation_end_date' => Carbon::now()->addDays($otherPatron->patron_type->no_of_day_reserve_allowed),
        ]);

        $data = ['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no];

        $this->actingAs($user)
            ->post(route('borrows.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'someone-reserve'])
            ->assertStatus(400);
    }

    public function test_it_redirects_to_login_page_when_creating_the_borrow_while_user_still_logged_out()
    {
        $this->post(route('borrows.store'), $this->borrow())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Get Patron Borrow Record
    public function test_it_can_get_the_patron_borrow_record()
    {
        $user = factory(User::class)->create();
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->post(route('borrows.patron-record'), ['patron_no' => $patron->patron_no])
            ->assertJsonStructure(['borrows', 'penalties'])
            ->assertStatus(200);
    }

    public function test_it_can_get_the_patron_borrow_record_even_the_user_is_library_assistant()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->post(route('borrows.patron-record'), ['patron_no' => $patron->patron_no])
            ->assertJsonStructure(['borrows', 'penalties'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_getting_the_patron_borrow_record()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('borrows.patron-record'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_redirects_to_login_page_when_getting_the_patron_borrow_record_while_user_still_logged_out()
    {
        $patron = factory(Patron::class)->create();

        $this->post(route('borrows.patron-record'), ['patron_no' => $patron->patron_no])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
