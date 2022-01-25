<?php

namespace Tests\Unit;

use Carbon\Carbon;
use App\Models\Accession;
use App\Models\Borrow;
use App\Models\Patron;
use App\Models\PatronAccount;
use App\Models\Penalty;
use App\Models\Reservation;
use App\Models\ReturnBook;

use App\Repositories\Accession\AccessionRepository;
use App\Repositories\Borrow\BorrowRepository;
use App\Repositories\Patron\PatronRepository;
use App\Repositories\Penalty\PenaltyRepository;
use App\Repositories\Reservation\ReservationRepository;
use App\Services\Reservation\PatronAccountReservationService;
use Illuminate\Support\Collection;

use ArgumentCountError;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatronAccountReservationServiceUnitTest extends TestCase
{
    use WithFaker;

    protected function initializeReservationService()
    {
        $reservationService = new PatronAccountReservationService(
            new AccessionRepository(new Accession()),
            new BorrowRepository(new Borrow()),
            new PatronRepository(new Patron()),
            new PenaltyRepository(new Penalty()),
            new ReservationRepository(new Reservation())
        );

        return $reservationService;
    }

    //Get Accession
    public function test_it_can_list_the_accessions()
    {
        factory(Accession::class, 3)->create();

        $reservationService = $this->initializeReservationService();
        $accessions = $reservationService->getAccession();

        $this->assertInstanceOf(Collection::class, $accessions);
    }

    //Get Reservation by patron id
    public function test_it_can_list_the_reservations_by_patron_id()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $this->be($patronAccount, 'patron');

        $reservationService = $this->initializeReservationService();
        $reservations = $reservationService->getReservationByPatronId();

        $this->assertInstanceOf(Collection::class, $reservations);
    }

    //Accession No.
    public function accessionNo()
    {
        $accession = factory(Accession::class)->create();
        return $accession->accession_no;
    }

    //Process Reservation Rules
    public function test_it_can_process_the_reservation_rules()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $this->be($patronAccount, 'patron');

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules($this->accessionNo());

        $this->assertIsString($result);
        $this->assertEquals($result, 'success');
    }

    public function test_it_throws_argument_count_error_when_processing_the_reservation_rules()
    {
        $this->expectException(ArgumentCountError::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->processReservationRules();
    }

    public function test_it_throws_model_not_found_error_when_processing_the_reservation_rules()
    {
        $this->expectException(ModelNotFoundException::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->processReservationRules($this->faker->unique()->isbn10);
    }

    public function test_it_throws_penalty_message_when_processing_the_reservation_rules()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $this->be($patronAccount, 'patron');

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

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules($this->accessionNo());

        $this->assertIsString($result);
        $this->assertEquals($result, 'penalty');
    }

    public function test_it_throws_maximum_reservation_exceed_message_when_processing_the_reservation_rules()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $this->be($patronAccount, 'patron');

        factory(Reservation::class, $patronAccount->patron->patron_type->no_of_reserve_allowed)->create([
            'patron_id' => $patronAccount->patron->id,
            'accession_id' => factory(Accession::class)->create()->id,
            'reservation_end_date' => Carbon::now()->addDays($patronAccount->patron->patron_type->no_of_day_reserve_allowed),
        ]);

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules($this->accessionNo());

        $this->assertIsString($result);
        $this->assertEquals($result, 'maximum-reservation-exceed');
    }

    public function test_it_throws_already_borrow_message_when_processing_the_reservation_rules()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $this->be($patronAccount, 'patron');

        $accession = factory(Accession::class)->create();

        factory(Borrow::class)->create([
            'patron_id' => $patronAccount->patron->id,
            'accession_id' => $accession->id,
        ]);

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules($accession->accession_no);

        $this->assertIsString($result);
        $this->assertEquals($result, 'already-borrow');
    }

    public function test_it_throws_someone_borrow_message_when_processing_the_reservation_rules()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $this->be($patronAccount, 'patron');

        $accession = factory(Accession::class)->create();

        factory(Borrow::class)->create([
            'patron_id' => factory(Patron::class)->create()->id,
            'accession_id' => $accession->id,
        ]);

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules($accession->accession_no);

        $this->assertIsString($result);
        $this->assertEquals($result, 'someone-borrow');
    }

    public function test_it_throws_already_reserve_message_when_processing_the_reservation_rules()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $this->be($patronAccount, 'patron');

        $accession = factory(Accession::class)->create();

        factory(Reservation::class)->create([
            'patron_id' => $patronAccount->patron->id,
            'accession_id' => $accession->id,
            'reservation_end_date' => Carbon::now()->addDays($patronAccount->patron->patron_type->no_of_day_reserve_allowed),
        ]);

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules($accession->accession_no);

        $this->assertIsString($result);
        $this->assertEquals($result, 'already-reserve');
    }

    public function test_it_throws_someone_reserve_message_when_processing_the_reservation_rules()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $this->be($patronAccount, 'patron');

        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(Reservation::class)->create([
            'patron_id' => $patron->id,
            'accession_id' => $accession->id,
            'reservation_end_date' => Carbon::now()->addDays($patron->patron_type->no_of_day_reserve_allowed),
        ]);

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules($accession->accession_no);

        $this->assertIsString($result);
        $this->assertEquals($result, 'someone-reserve');
    }

    //Create Reservation
    public function test_it_can_create_the_reservation()
    {
        $patronAccount = factory(PatronAccount::class)->create();
        $this->be($patronAccount, 'patron');

        $reservationService = $this->initializeReservationService();
        $reservation = $reservationService->create($this->accessionNo());

        $this->assertInstanceOf(Reservation::class, $reservation);
    }

    public function test_it_throws_argument_count_error_when_creating_the_reservation()
    {
        $this->expectException(ArgumentCountError::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->create();
    }

    public function test_it_throws_model_not_found_error_when_creating_the_reservation()
    {
        $this->expectException(ModelNotFoundException::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->create($this->faker->unique()->isbn10);
    }
}
