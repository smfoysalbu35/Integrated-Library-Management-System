<?php

namespace Tests\Unit;

use Carbon\Carbon;
use App\Models\Accession;
use App\Models\Borrow;
use App\Models\Patron;
use App\Models\PatronAttendanceLog;
use App\Models\Penalty;
use App\Models\Reservation;
use App\Models\ReturnBook;

use App\Repositories\Accession\AccessionRepository;
use App\Repositories\Borrow\BorrowRepository;
use App\Repositories\Patron\PatronRepository;
use App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepository;
use App\Repositories\Penalty\PenaltyRepository;
use App\Repositories\Reservation\ReservationRepository;
use App\Services\Reservation\ReservationService;
use Illuminate\Support\Collection;

use ArgumentCountError;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationServiceUnitTest extends TestCase
{
    use WithFaker;

    protected function initializeReservationService()
    {
        $reservationService = new ReservationService(
            new AccessionRepository(new Accession()),
            new BorrowRepository(new Borrow()),
            new PatronRepository(new Patron()),
            new PatronAttendanceLogRepository(new PatronAttendanceLog()),
            new PenaltyRepository(new Penalty()),
            new ReservationRepository(new Reservation())
        );

        return $reservationService;
    }

    //Get Reservation
    public function test_it_can_list_the_reservations()
    {
        factory(Reservation::class, 3)->create();

        $reservationService = $this->initializeReservationService();
        $reservations = $reservationService->get();

        $this->assertInstanceOf(Collection::class, $reservations);
    }

    //Process Reservation Rules
    public function test_it_can_process_the_reservation_rules()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'success');
    }

    public function test_it_throws_argument_count_error_when_processing_the_reservation_rules()
    {
        $this->expectException(ArgumentCountError::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->processReservationRules();
    }

    public function test_it_throws_error_exception_when_processing_the_reservation_rules()
    {
        $this->expectException(ErrorException::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->processReservationRules([]);
    }

    public function test_it_throws_model_not_found_error_when_processing_the_reservation_rules()
    {
        $this->expectException(ModelNotFoundException::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->processReservationRules(['patron_no' => $this->faker->unique()->isbn10, 'accession_no' => $this->faker->unique()->isbn10]);
    }

    public function test_it_throws_patron_not_login_message_when_processing_the_reservation_rules()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'patron-not-login');
    }

    public function test_it_throws_penalty_message_when_processing_the_reservation_rules()
    {
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

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'penalty');
    }

    public function test_it_throws_maximum_reservation_exceed_message_when_processing_the_reservation_rules()
    {
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

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'maximum-reservation-exceed');
    }

    public function test_it_throws_already_borrow_message_when_processing_the_reservation_rules()
    {
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

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'already-borrow');
    }

    public function test_it_throws_someone_borrow_message_when_processing_the_reservation_rules()
    {
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

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'someone-borrow');
    }

    public function test_it_throws_already_reserve_message_when_processing_the_reservation_rules()
    {
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

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'already-reserve');
    }

    public function test_it_throws_someone_reserve_message_when_processing_the_reservation_rules()
    {
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

        $reservationService = $this->initializeReservationService();
        $result = $reservationService->processReservationRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'someone-reserve');
    }

    //Create Reservation
    public function test_it_can_create_the_reservation()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        $reservationService = $this->initializeReservationService();
        $reservation = $reservationService->create(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertInstanceOf(Reservation::class, $reservation);
    }

    public function test_it_throws_argument_count_error_when_creating_the_reservation()
    {
        $this->expectException(ArgumentCountError::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->create();
    }

    public function test_it_throws_error_exception_when_creating_the_reservation()
    {
        $this->expectException(ErrorException::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->create([]);
    }

    public function test_it_throws_model_not_found_error_when_creating_the_reservation()
    {
        $this->expectException(ModelNotFoundException::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->create(['patron_no' => $this->faker->unique()->isbn10, 'accession_no' => $this->faker->unique()->isbn10]);
    }

    //Get Patron Reservation
    public function test_it_can_list_the_patron_reservation_record()
    {
        $patron = factory(Patron::class)->create();

        $reservationService = $this->initializeReservationService();
        $reservations = $reservationService->getPatronReservation($patron->patron_no);

        $this->assertInstanceOf(Collection::class, $reservations);
    }

    public function test_it_throws_argument_count_error_when_listing_the_patron_reservation_record()
    {
        $this->expectException(ArgumentCountError::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->getPatronReservation();
    }

    public function test_it_throws_model_not_found_error_when_listing_the_patron_reservation_record()
    {
        $this->expectException(ModelNotFoundException::class);

        $reservationService = $this->initializeReservationService();
        $reservationService->getPatronReservation($this->faker->unique()->isbn10);
    }
}
