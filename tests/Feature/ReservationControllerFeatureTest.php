<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Models\Accession;
use App\Models\Borrow;
use App\Models\Patron;
use App\Models\PatronAttendanceLog;
use App\Models\Penalty;
use App\Models\ReturnBook;
use App\Models\Reservation;
use App\Models\User;
use Tests\TestCase;

class ReservationControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_reservations_list_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('reservations.index'))
            ->assertStatus(200)
            ->assertSee('Reservation List');
    }

    public function test_it_throws_authorization_error_when_displaying_the_reservations_list_page()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('reservations.index'))
            ->assertStatus(403);
    }

    public function test_it_redirects_to_login_page_when_displaying_the_reservations_list_page_while_user_still_logged_out()
    {
        $this->get(route('reservations.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //create
    public function test_it_can_display_the_create_reservation_page()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('reservations.create'))
            ->assertStatus(200)
            ->assertSee('Reserve Book');
    }

    public function test_it_can_display_the_create_reservation_page_even_the_user_is_library_assistant()
    {
        $user = factory(User::class)->create(['user_type' => 2]);

        $this->actingAs($user)
            ->get(route('reservations.create'))
            ->assertStatus(200)
            ->assertSee('Reserve Book');
    }

    public function test_it_redirects_to_login_page_when_displaying_the_create_reservation_page_while_user_still_logged_out()
    {
        $this->get(route('reservations.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Reservation Data
    public function reservation()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        return [
            'patron_no' => $patron->patron_no,
            'accession_no' => $accession->accession_no,
        ];
    }

    //store
    public function test_it_can_create_the_reservation()
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
            ->post(route('reservations.store'), $data)
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_can_create_the_reservation_even_the_user_is_library_assistant()
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
            ->post(route('reservations.store'), $data)
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_during_creating_the_reservation()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('reservations.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_error_when_patron_not_login_in_patron_attendance_log_during_creating_the_reservation()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('reservations.store'), $this->reservation())
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'patron-not-login'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_has_penalty_during_creating_the_reservation()
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
            ->post(route('reservations.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'penalty'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_exceed_the_maximum_no_of_reservation_allowed_during_creating_the_reservation()
    {
        $user = factory(User::class)->create();

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        factory(Reservation::class, $patron->patron_type->no_of_reserve_allowed)->create([
            'patron_id' => $patron->id,
            'accession_id' => factory(Accession::class)->create()->id,
            'reservation_end_date' => Carbon::now()->addDays($patron->patron_type->no_of_day_reserve_allowed),
        ]);

        $data = ['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no];

        $this->actingAs($user)
            ->post(route('reservations.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'maximum-reservation-exceed'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_already_borrow_the_book_during_creating_the_reservation()
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
            ->post(route('reservations.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'already-borrow'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_someone_borrow_the_book_during_creating_the_reservation()
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
            ->post(route('reservations.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'someone-borrow'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_already_reserve_the_book_during_creating_the_reservation()
    {
        $user = factory(User::class)->create();

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        factory(Reservation::class)->create([
            'patron_id' => $patron->id,
            'accession_id' => $accession->id,
            'reservation_end_date' => Carbon::now()->addDays($patron->patron_type->no_of_day_reserve_allowed),
        ]);

        $data = ['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no];

        $this->actingAs($user)
            ->post(route('reservations.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'already-reserve'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_someone_reserve_the_book_during_creating_the_reservation()
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
            ->post(route('reservations.store'), $data)
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'someone-reserve'])
            ->assertStatus(400);
    }

    public function test_it_redirects_to_login_page_when_creating_the_reservation_while_user_still_logged_out()
    {
        $this->post(route('reservations.store'), $this->reservation())
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }

    //Get Patron Reservation Record
    public function test_it_can_get_the_patron_reservation_record()
    {
        $user = factory(User::class)->create();
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->post(route('reservations.patron-record'), ['patron_no' => $patron->patron_no])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_can_get_the_patron_reservation_record_even_the_user_is_library_assistant()
    {
        $user = factory(User::class)->create(['user_type' => 2]);
        $patron = factory(Patron::class)->create();

        $this->actingAs($user)
            ->post(route('reservations.patron-record'), ['patron_no' => $patron->patron_no])
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_throws_validation_error_when_getting_the_patron_reservation_record()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('reservations.patron-record'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_redirects_to_login_page_when_getting_the_patron_reservation_record_while_user_still_logged_out()
    {
        $patron = factory(Patron::class)->create();

        $this->post(route('reservations.patron-record'), ['patron_no' => $patron->patron_no])
            ->assertStatus(302)
            ->assertRedirect(route('login.index'));
    }
}
