<?php

namespace App\Services\Borrow;

use App\Repositories\Accession\AccessionRepositoryInterface;
use App\Repositories\Borrow\BorrowRepositoryInterface;
use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepositoryInterface;
use App\Repositories\Penalty\PenaltyRepositoryInterface;
use App\Repositories\Reservation\ReservationRepositoryInterface;

class BorrowService
{
    protected $accession, $borrow, $patron, $patronAttendanceLog, $penalty, $reservation;

    public function __construct(
        AccessionRepositoryInterface $accession,
        BorrowRepositoryInterface $borrow,
        PatronRepositoryInterface $patron,
        PatronAttendanceLogRepositoryInterface $patronAttendanceLog,
        PenaltyRepositoryInterface $penalty,
        ReservationRepositoryInterface $reservation
    )
    {
        $this->accession = $accession;
        $this->borrow = $borrow;
        $this->patron = $patron;
        $this->patronAttendanceLog = $patronAttendanceLog;
        $this->penalty = $penalty;
        $this->reservation = $reservation;
    }

    public function get()
    {
        $borrows = $this->borrow->get();
        return $borrows;
    }

    public function processBorrowingRules(array $data)
    {
        $accession = $this->accession->findBy('accession_no', $data['accession_no']);
        $patron = $this->patron->findBy('patron_no', $data['patron_no']);

        if(!($this->patronAttendanceLog->checkPatronAttendanceLog($patron->id) > 0))
            return 'patron-not-login';

        if($this->penalty->getPenaltyCountByPatronId($patron->id) > 0)
            return 'penalty';

        if($this->borrow->getBorrowCountByPatronId($patron->id) >= $patron->patron_type->no_of_borrow_allowed)
            return 'maximum-borrow-exceed';

        if($this->borrow->getBorrowCountByAccessionId($accession->id) > 0)
        {
            if($this->borrow->checkIfPatronBorrowAccession($accession->id, $patron->id) > 0)
                return 'already-borrow';

            return 'someone-borrow';
        }

        if($this->reservation->getReservationCountByAccessionId($accession->id) > 0)
            if(!($this->reservation->checkIfPatronReserveAccession($accession->id, $patron->id) > 0))
                return 'someone-reserve';

        return 'success';
    }

    public function create(array $data)
    {
        $accession = $this->accession->findBy('accession_no', $data['accession_no']);
        $patron = $this->patron->findBy('patron_no', $data['patron_no']);

        $borrow = $this->borrow->create([
            'patron_id' => $patron->id,
            'accession_id' => $accession->id,
            'borrow_date' => NOW(),
            'borrow_time' => NOW(),
            'status' => 1,
        ]);

        $accession = $this->accession->update(['status' => 0], $accession->id);

        return $borrow;
    }

    public function getPatronBorrow(string $patronNo)
    {
        $patron = $this->patron->findBy('patron_no', $patronNo);
        $borrows = $this->borrow->getBorrowByPatronId($patron->id);
        return $borrows;
    }

    public function getPatronPenalty(string $patronNo)
    {
        $patron = $this->patron->findBy('patron_no', $patronNo);
        $penalties = $this->penalty->getPenaltyByPatronId($patron->id);
        return $penalties;
    }
}
