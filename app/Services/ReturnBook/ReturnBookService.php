<?php

namespace App\Services\ReturnBook;

use Carbon\Carbon;
use App\Repositories\Accession\AccessionRepositoryInterface;
use App\Repositories\Borrow\BorrowRepositoryInterface;
use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepositoryInterface;
use App\Repositories\Penalty\PenaltyRepositoryInterface;
use App\Repositories\ReturnBook\ReturnBookRepositoryInterface;

class ReturnBookService
{
    protected $accession, $borrow, $patron, $patronAttendanceLog, $penalty, $returnBook;

    public function __construct(
        AccessionRepositoryInterface $accession,
        BorrowRepositoryInterface $borrow,
        PatronRepositoryInterface $patron,
        PatronAttendanceLogRepositoryInterface $patronAttendanceLog,
        PenaltyRepositoryInterface $penalty,
        ReturnBookRepositoryInterface $returnBook
    )
    {
        $this->accession = $accession;
        $this->borrow = $borrow;
        $this->patron = $patron;
        $this->patronAttendanceLog = $patronAttendanceLog;
        $this->penalty = $penalty;
        $this->returnBook = $returnBook;
    }

    public function get()
    {
        $returnBooks = $this->returnBook->get();
        return $returnBooks;
    }

    public function processReturningBookRules(array $data)
    {
        $accession = $this->accession->findBy('accession_no', $data['accession_no']);
        $patron = $this->patron->findBy('patron_no', $data['patron_no']);

        if(!($this->patronAttendanceLog->checkPatronAttendanceLog($patron->id) > 0))
            return 'patron-not-login';

        if(!($this->borrow->checkIfPatronBorrowAccession($accession->id, $patron->id) > 0))
            return 'someone-borrow';

        return 'success';
    }

    public function create(array $data)
    {
        $accession = $this->accession->findBy('accession_no', $data['accession_no']);
        $patron = $this->patron->findBy('patron_no', $data['patron_no']);
        $borrow = $this->borrow->findPatronBorrowAccession($accession->id, $patron->id);

        $returnBook = $this->returnBook->create([
            'borrow_id' => $borrow->id,
            'patron_id' => $patron->id,
            'accession_id' => $accession->id,
            'return_date' => Carbon::now(),
            'return_time' => Carbon::now(),
        ]);

        $this->borrow->update(['status' => 0], $borrow->id);
        $this->accession->update(['status' => 1], $accession->id);

        $diffInDays = Carbon::parse($borrow->borrow_date)->diffInDays(Carbon::now());
        $dueDate = Carbon::parse($borrow->borrow_date)->addDays($patron->patron_type->no_of_day_borrow_allowed)->format('Y-m-d');

        if($diffInDays > $patron->patron_type->no_of_day_borrow_allowed)
        {
            $this->penalty->create([
                'return_book_id' => $returnBook->id,
                'patron_id' => $patron->id,
                'accession_id' => $accession->id,
                'penalty_due_date' => $dueDate,
                'amount' => $patron->patron_type->fines * ($diffInDays - $patron->patron_type->no_of_day_borrow_allowed),
                'overdue' => $diffInDays - $patron->patron_type->no_of_day_borrow_allowed,
                'status' => 1,
            ]);
        }

        return $returnBook;
    }
}
