<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use App\Repositories\Reservation\ReservationRepositoryInterface;

class ReservationController extends Controller
{
    protected $reservation;

    public function __construct(ReservationRepositoryInterface $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = $this->reservation->getBy('patron_id', auth()->guard('patron')->user()->id);
        return view('patron-web.reservation.index', compact('reservations'));
    }
}
