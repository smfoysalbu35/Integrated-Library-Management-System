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
use App\Services\Borrow\BorrowService;
use Illuminate\Support\Collection;

use ArgumentCountError;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BorrowServiceUnitTest extends TestCase
{
    use WithFaker;

    protected function initializeBorrowService()
    {
        $borrowService = new BorrowService(
            new AccessionRepository(new Accession),
            new BorrowRepository(new Borrow),
            new PatronRepository(new Patron),
            new PatronAttendanceLogRepository(new PatronAttendanceLog),
            new PenaltyRepository(new Penalty),
            new ReservationRepository(new Reservation)
        );

        return $borrowService;
    }

    //Get Borrows
    public function test_it_can_list_the_borrows()
    {
        factory(Borrow::class, 3)->create();

        $borrowService = $this->initializeBorrowService();
        $borrows = $borrowService->get();

        $this->assertInstanceOf(Collection::class, $borrows);
    }

    //Process Borrowing Rules
    public function test_it_can_process_the_borrowing_rules()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(PatronAttendanceLog::class)->create([
            'patron_id' => $patron->id,
            'date_out' => NULL,
            'time_out' => NULL,
        ]);

        $borrowService = $this->initializeBorrowService();
        $result = $borrowService->processBorrowingRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'success');
    }

    public function test_it_throws_argument_count_error_when_processing_the_borrowing_rules()
    {
        $this->expectException(ArgumentCountError::class);

        $borrowService = $this->initializeBorrowService();
        $borrowService->processBorrowingRules();
    }

    public function test_it_throws_error_exception_when_processing_the_borrowing_rules()
    {
        $this->expectException(ErrorException::class);

        $borrowService = $this->initializeBorrowService();
        $borrowService->processBorrowingRules([]);
    }

    public function test_it_throws_model_not_found_error_when_processing_the_borrowing_rules()
    {
        $this->expectException(ModelNotFoundException::class);

        $borrowService = $this->initializeBorrowService();
        $borrowService->processBorrowingRules(['patron_no' => $this->faker->unique()->isbn10, 'accession_no' => $this->faker->unique()->isbn10]);
    }

    public function test_it_throws_patron_not_login_message_when_processing_the_borrowing_rules()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        $borrowService = $this->initializeBorrowService();
        $result = $borrowService->processBorrowingRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'patron-not-login');
    }

    public function test_it_throws_penalty_message_when_processing_the_borrowing_rules()
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

        $borrowService = $this->initializeBorrowService();
        $result = $borrowService->processBorrowingRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'penalty');
    }

    public function test_it_throws_maximum_borrow_exceed_message_when_processing_the_borrowing_rules()
    {
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

        $borrowService = $this->initializeBorrowService();
        $result = $borrowService->processBorrowingRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'maximum-borrow-exceed');
    }

    public function test_it_throws_already_borrow_message_when_processing_the_borrowing_rules()
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

        $borrowService = $this->initializeBorrowService();
        $result = $borrowService->processBorrowingRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'already-borrow');
    }

    public function test_it_throws_someone_borrow_message_when_processing_the_borrowing_rules()
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

        $borrowService = $this->initializeBorrowService();
        $result = $borrowService->processBorrowingRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'someone-borrow');
    }

    public function test_it_throws_someone_reserve_message_when_processing_the_borrowing_rules()
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

        $borrowService = $this->initializeBorrowService();
        $result = $borrowService->processBorrowingRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'someone-reserve');
    }

    //Create Borrow
    public function test_it_can_create_the_borrow()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        $borrowService = $this->initializeBorrowService();
        $borrow = $borrowService->create(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertInstanceOf(Borrow::class, $borrow);
    }

    public function test_it_throws_argument_count_error_when_creating_the_borrow()
    {
        $this->expectException(ArgumentCountError::class);

        $borrowService = $this->initializeBorrowService();
        $borrowService->create();
    }

    public function test_it_throws_error_exception_when_creating_the_borrow()
    {
        $this->expectException(ErrorException::class);

        $borrowService = $this->initializeBorrowService();
        $borrowService->create([]);
    }

    public function test_it_throws_model_not_found_error_when_creating_the_borrow()
    {
        $this->expectException(ModelNotFoundException::class);

        $borrowService = $this->initializeBorrowService();
        $borrowService->create(['patron_no' => $this->faker->unique()->isbn10, 'accession_no' => $this->faker->unique()->isbn10]);
    }

    //Get Patron Borrow
    public function test_it_can_list_the_patron_borrow_record()
    {
        $patron = factory(Patron::class)->create();

        $borrowService = $this->initializeBorrowService();
        $borrows = $borrowService->getPatronBorrow($patron->patron_no);

        $this->assertInstanceOf(Collection::class, $borrows);
    }

    public function test_it_throws_argument_count_error_when_listing_the_patron_borrow_record()
    {
        $this->expectException(ArgumentCountError::class);

        $borrowService = $this->initializeBorrowService();
        $borrowService->getPatronBorrow();
    }

    public function test_it_throws_model_not_found_error_when_listing_the_patron_borrow_record()
    {
        $this->expectException(ModelNotFoundException::class);

        $borrowService = $this->initializeBorrowService();
        $borrowService->getPatronBorrow($this->faker->unique()->isbn10);
    }

    //Get Patron Penalty
    public function test_it_can_list_the_patron_penalty_record()
    {
        $patron = factory(Patron::class)->create();

        $borrowService = $this->initializeBorrowService();
        $penalties = $borrowService->getPatronPenalty($patron->patron_no);

        $this->assertInstanceOf(Collection::class, $penalties);
    }

    public function test_it_throws_argument_count_error_when_listing_the_patron_penalty_record()
    {
        $this->expectException(ArgumentCountError::class);

        $borrowService = $this->initializeBorrowService();
        $borrowService->getPatronPenalty();
    }

    public function test_it_throws_model_not_found_error_when_listing_the_patron_penalty_record()
    {
        $this->expectException(ModelNotFoundException::class);

        $borrowService = $this->initializeBorrowService();
        $borrowService->getPatronPenalty($this->faker->unique()->isbn10);
    }
}
