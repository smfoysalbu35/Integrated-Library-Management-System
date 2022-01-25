<?php

namespace Tests\Unit;

use App\Models\Accession;
use App\Models\Borrow;
use App\Models\Patron;
use App\Models\PatronAttendanceLog;
use App\Models\Penalty;
use App\Models\ReturnBook;

use App\Repositories\Accession\AccessionRepository;
use App\Repositories\Borrow\BorrowRepository;
use App\Repositories\Patron\PatronRepository;
use App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepository;
use App\Repositories\Penalty\PenaltyRepository;
use App\Repositories\ReturnBook\ReturnBookRepository;
use App\Services\ReturnBook\ReturnBookService;
use Illuminate\Support\Collection;

use ArgumentCountError;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReturnBookServiceUnitTest extends TestCase
{
    use WithFaker;

    protected function initializeReturnBookService()
    {
        $returnBookService = new ReturnBookService(
            new AccessionRepository(new Accession),
            new BorrowRepository(new Borrow),
            new PatronRepository(new Patron),
            new PatronAttendanceLogRepository(new PatronAttendanceLog),
            new PenaltyRepository(new Penalty),
            new ReturnBookRepository(new ReturnBook)
        );

        return $returnBookService;
    }

    //Get Return Book
    public function test_it_can_list_the_return_books()
    {
        factory(ReturnBook::class, 3)->create();

        $returnBookService = $this->initializeReturnBookService();
        $returnBooks = $returnBookService->get();

        $this->assertInstanceOf(Collection::class, $returnBooks);
    }

    //Process Returning Book Rules
    public function test_it_can_process_the_return_book_rules()
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

        $returnBookService = $this->initializeReturnBookService();
        $result = $returnBookService->processReturningBookRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'success');
    }

    public function test_it_throws_argument_count_error_when_processing_the_returning_book_rules()
    {
        $this->expectException(ArgumentCountError::class);

        $returnBookService = $this->initializeReturnBookService();
        $returnBookService->processReturningBookRules();
    }

    public function test_it_throws_error_exception_when_processing_the_returning_book_rules()
    {
        $this->expectException(ErrorException::class);

        $returnBookService = $this->initializeReturnBookService();
        $returnBookService->processReturningBookRules([]);
    }

    public function test_it_throws_model_not_found_error_when_processing_the_returning_book_rules()
    {
        $this->expectException(ModelNotFoundException::class);

        $returnBookService = $this->initializeReturnBookService();
        $returnBookService->processReturningBookRules(['patron_no' => $this->faker->unique()->isbn10, 'accession_no' => $this->faker->unique()->isbn10]);
    }

    public function test_it_throws_patron_not_login_message_when_processing_the_returning_book_rules()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        $returnBookService = $this->initializeReturnBookService();
        $result = $returnBookService->processReturningBookRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'patron-not-login');
    }

    public function test_it_throws_someone_borrow_message_when_processing_the_returning_book_rules()
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

        $returnBookService = $this->initializeReturnBookService();
        $result = $returnBookService->processReturningBookRules(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertIsString($result);
        $this->assertEquals($result, 'someone-borrow');
    }

    //Create Return Book
    public function test_it_can_create_the_return_book()
    {
        $patron = factory(Patron::class)->create();
        $accession = factory(Accession::class)->create();

        factory(Borrow::class)->create([
            'patron_id' => $patron->id,
            'accession_id' => $accession->id,
        ]);

        $returnBookService = $this->initializeReturnBookService();
        $returnBook = $returnBookService->create(['patron_no' => $patron->patron_no, 'accession_no' => $accession->accession_no]);

        $this->assertInstanceOf(ReturnBook::class, $returnBook);
    }

    public function test_it_throws_argument_count_error_when_creating_the_return_book()
    {
        $this->expectException(ArgumentCountError::class);

        $returnBookService = $this->initializeReturnBookService();
        $returnBookService->create();
    }

    public function test_it_throws_error_exception_when_creating_the_return_book()
    {
        $this->expectException(ErrorException::class);

        $returnBookService = $this->initializeReturnBookService();
        $returnBookService->create([]);
    }

    public function test_it_throws_model_not_found_error_when_creating_the_return_book()
    {
        $this->expectException(ModelNotFoundException::class);

        $returnBookService = $this->initializeReturnBookService();
        $returnBookService->create(['patron_no' => $this->faker->unique()->isbn10, 'accession_no' => $this->faker->unique()->isbn10]);
    }
}
