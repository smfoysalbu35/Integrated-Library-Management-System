<?php

namespace App\Http\Controllers;

use App\Repositories\Accession\AccessionRepositoryInterface;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Borrow\BorrowRepositoryInterface;
use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\Reservation\ReservationRepositoryInterface;
use App\Repositories\ReturnBook\ReturnBookRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class DashboardController extends Controller
{
    protected $accession, $book, $borrow, $patron, $reservation, $returnBook, $transaction, $user;

    public function __construct(
        AccessionRepositoryInterface $accession,
        BookRepositoryInterface $book,
        BorrowRepositoryInterface $borrow,
        PatronRepositoryInterface $patron,
        ReservationRepositoryInterface $reservation,
        ReturnBookRepositoryInterface $returnBook,
        TransactionRepositoryInterface $transaction,
        UserRepositoryInterface $user
    )
    {
        $this->accession = $accession;
        $this->book = $book;
        $this->borrow = $borrow;
        $this->patron = $patron;
        $this->reservation = $reservation;
        $this->returnBook = $returnBook;
        $this->transaction = $transaction;
        $this->user = $user;
    }

    public function index()
    {
        $accessionCount = $this->accession->count();
        $bookCount = $this->book->count();
        $patronCount = $this->patron->count();
        $userCount = $this->user->count();

        $borrows = $this->borrow->getLatestBorrowTransaction();
        $returnBooks = $this->returnBook->getLatestReturnBookTransaction();
        $reservations = $this->reservation->getLatestReservationTransaction();
        $transactions = $this->transaction->getLatestTransaction();

        return view('admin.index', compact('accessionCount', 'bookCount', 'patronCount', 'userCount', 'borrows', 'returnBooks', 'reservations', 'transactions'));
    }
}
