<?php

namespace Tests\Feature\Patron;

use Carbon\Carbon;
use App\Models\Accession;
use App\Models\Borrow;
use App\Models\Patron;
use App\Models\PatronAccount;
use App\Models\Penalty;
use App\Models\ReturnBook;
use App\Models\Reservation;
use Tests\TestCase;

class HomeControllerFeatureTest extends TestCase
{
    //index
    public function test_it_can_display_the_patron_account_home_page()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($patronAccount, 'patron')
            ->get(route('patron-web.home'))
            ->assertStatus(200)
            ->assertSee('Home')
            ->assertSee('Accession List')
            ->assertSee('Reserve Book');
    }

    public function test_it_redirects_to_patron_account_login_page_when_displaying_the_patron_account_home_page_while_patron_account_still_logged_out()
    {
        $this->get(route('patron-web.home'))
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.login.index'));
    }

    //list
    public function test_it_can_list_the_patron_account_reservations()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($patronAccount, 'patron')
            ->get(route('patron-web.home.reservations.list'))
            ->assertJsonStructure(['data'])
            ->assertStatus(200);
    }

    public function test_it_redirects_to_patron_account_login_page_when_listing_the_patron_account_reservations_while_patron_account_still_logged_out()
    {
        $this->get(route('patron-web.home.reservations.list'))
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.login.index'));
    }

    //Reservation Data
    public function reservation()
    {
        $accession = factory(Accession::class)->create();

        return [
            'accession_no' => $accession->accession_no,
        ];
    }

    //store
    public function test_it_can_create_the_reservation()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($patronAccount, 'patron')
            ->post(route('patron-web.home.reservations.store'), $this->reservation())
            ->assertJsonStructure(['message', 'data'])
            ->assertStatus(201);
    }

    public function test_it_throws_validation_error_during_creating_the_reservation()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $this->actingAs($patronAccount, 'patron')
            ->post(route('patron-web.home.reservations.store'), [])
            ->assertSessionHas(['errors']);
    }

    public function test_it_throws_error_when_patron_has_penalty_during_creating_the_reservation()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $borrow = factory(Borrow::class)->create([
            'patron_id' => $patronAccount->patron->id,
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

        $this->actingAs($patronAccount, 'patron')
            ->post(route('patron-web.home.reservations.store'), $this->reservation())
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'penalty'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_exceed_the_maximum_no_of_reservation_allowed_during_creating_the_reservation()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        factory(Reservation::class, $patronAccount->patron->patron_type->no_of_reserve_allowed)->create([
            'patron_id' => $patronAccount->patron->id,
            'accession_id' => factory(Accession::class)->create()->id,
            'reservation_end_date' => Carbon::now()->addDays($patronAccount->patron->patron_type->no_of_day_reserve_allowed),
        ]);

        $this->actingAs($patronAccount, 'patron')
            ->post(route('patron-web.home.reservations.store'), $this->reservation())
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'maximum-reservation-exceed'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_already_borrow_the_book_during_creating_the_reservation()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $accession = factory(Accession::class)->create();

        factory(Borrow::class)->create([
            'patron_id' => $patronAccount->patron->id,
            'accession_id' => $accession->id,
        ]);

        $this->actingAs($patronAccount, 'patron')
            ->post(route('patron-web.home.reservations.store'), ['accession_no' => $accession->accession_no])
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'already-borrow'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_someone_borrow_the_book_during_creating_the_reservation()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $accession = factory(Accession::class)->create();

        factory(Borrow::class)->create([
            'patron_id' => factory(Patron::class)->create()->id,
            'accession_id' => $accession->id,
        ]);

        $this->actingAs($patronAccount, 'patron')
            ->post(route('patron-web.home.reservations.store'), ['accession_no' => $accession->accession_no])
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'someone-borrow'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_patron_already_reserve_the_book_during_creating_the_reservation()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $accession = factory(Accession::class)->create();

        factory(Reservation::class)->create([
            'patron_id' => $patronAccount->patron->id,
            'accession_id' => $accession->id,
            'reservation_end_date' => Carbon::now()->addDays($patronAccount->patron->patron_type->no_of_day_reserve_allowed),
        ]);

        $this->actingAs($patronAccount, 'patron')
            ->post(route('patron-web.home.reservations.store'), ['accession_no' => $accession->accession_no])
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'already-reserve'])
            ->assertStatus(400);
    }

    public function test_it_throws_error_when_someone_reserve_the_book_during_creating_the_reservation()
    {
        $patronAccount = factory(PatronAccount::class)->create();

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(Reservation::class)->create([
            'patron_id' => $patron->id,
            'accession_id' => $accession->id,
            'reservation_end_date' => Carbon::now()->addDays($patron->patron_type->no_of_day_reserve_allowed),
        ]);

        $this->actingAs($patronAccount, 'patron')
            ->post(route('patron-web.home.reservations.store'), ['accession_no' => $accession->accession_no])
            ->assertJsonStructure(['error', 'message'])
            ->assertJson(['error' => 'someone-reserve'])
            ->assertStatus(400);
    }

    public function test_it_redirects_to_patron_account_login_page_when_creating_the_reservation_while_patron_account_still_logged_out()
    {
        $this->post(route('patron-web.home.reservations.store'), $this->reservation())
            ->assertStatus(302)
            ->assertRedirect(route('patron-web.login.index'));
    }
}
