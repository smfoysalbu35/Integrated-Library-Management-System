<?php

namespace App\Services\Reservation;

use Carbon\Carbon;
use App\Repositories\Accession\AccessionRepositoryInterface;
use App\Repositories\Borrow\BorrowRepositoryInterface;
use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepositoryInterface;
use App\Repositories\Penalty\PenaltyRepositoryInterface;
use App\Repositories\Reservation\ReservationRepositoryInterface;

class ReservationService
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
        $reservations = $this->reservation->get();
        return $reservations;
    }

    public function processReservationRules(array $data)
    {
        $accession = $this->accession->findBy('accession_no', $data['accession_no']);
        $patron = $this->patron->findBy('patron_no', $data['patron_no']);

        if(!($this->patronAttendanceLog->checkPatronAttendanceLog($patron->id) > 0))
            return 'patron-not-login';

        if($this->penalty->getPenaltyCountByPatronId($patron->id) > 0)
            return 'penalty';

        if($this->reservation->getReservationCountByPatronId($patron->id) >= $patron->patron_type->no_of_reserve_allowed)
            return 'maximum-reservation-exceed';

        if($this->borrow->getBorrowCountByAccessionId($accession->id) > 0)
        {
            if($this->borrow->checkIfPatronBorrowAccession($accession->id, $patron->id) > 0)
                return 'already-borrow';

            return 'someone-borrow';
        }

        if($this->reservation->getReservationCountByAccessionId($accession->id) > 0)
        {
            if($this->reservation->checkIfPatronReserveAccession($accession->id, $patron->id) > 0)
                return 'already-reserve';

            return 'someone-reserve';
        }

        return 'success';
    }

    public function create(array $data)
    {
        $accession = $this->accession->findBy('accession_no', $data['accession_no']);
        $patron = $this->patron->findBy('patron_no', $data['patron_no']);

        $reservation = $this->reservation->create([
            'patron_id' => $patron->id,
            'accession_id' => $accession->id,
            'reservation_date' => Carbon::now(),
            'reservation_time' => Carbon::now(),
            'reservation_end_date' => Carbon::now()->addDays($patron->patron_type->no_of_day_reserve_allowed),
        ]);

        $accession = $this->accession->update(['status' => 0], $accession->id);

        return $reservation;
    }

    public function getPatronReservation(string $patronNo)
    {
        $patron = $this->patron->findBy('patron_no', $patronNo);
        $reservations = $this->reservation->getReservationByPatronId($patron->id);
        return $reservations;
    }
}
