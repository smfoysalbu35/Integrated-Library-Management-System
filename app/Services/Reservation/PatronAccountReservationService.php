<?php

namespace App\Services\Reservation;

use Carbon\Carbon;
use App\Repositories\Accession\AccessionRepositoryInterface;
use App\Repositories\Borrow\BorrowRepositoryInterface;
use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\Penalty\PenaltyRepositoryInterface;
use App\Repositories\Reservation\ReservationRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class PatronAccountReservationService
{
    protected $accession, $borrow, $patron, $penalty, $reservation;

    public function __construct(
        AccessionRepositoryInterface $accession,
        BorrowRepositoryInterface $borrow,
        PatronRepositoryInterface $patron,
        PenaltyRepositoryInterface $penalty,
        ReservationRepositoryInterface $reservation
    )
    {
        $this->accession = $accession;
        $this->borrow = $borrow;
        $this->patron = $patron;
        $this->penalty = $penalty;
        $this->reservation = $reservation;
    }

    public function getAccession()
    {
        $accessions = $this->accession->get();
        return $accessions;
    }

    public function getReservationByPatronId()
    {
        $reservations = $this->reservation->getReservationByPatronId($this->guard()->user()->patron->id);
        return $reservations;
    }

    public function processReservationRules(string $accessionNo)
    {
        $accession = $this->accession->findBy('accession_no', $accessionNo);
        $patron = $this->patron->find($this->guard()->user()->patron->id);

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

    public function create(string $accessionNo)
    {
        $accession = $this->accession->findBy('accession_no', $accessionNo);
        $patron = $this->patron->find($this->guard()->user()->patron->id);

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

    protected function guard()
    {
        return Auth::guard('patron');
    }
}
