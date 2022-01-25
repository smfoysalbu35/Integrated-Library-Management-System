<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatronNoRequest;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Services\Reservation\ReservationService;
use App\Http\Resources\ReservationResource;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Reservation::class);

        $reservations = $this->reservationService->get();
        return view('admin.circulation.reservation.list', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Reservation::class);

        return view('admin.circulation.reservation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationRequest $request)
    {
        $this->authorize('create', Reservation::class);

        $result = $this->reservationService->processReservationRules($request->except('_token'));

        if($result == 'patron-not-login')
            return response()->json(['error' => 'patron-not-login', 'message' => 'Patron doesn\'t login in the library.'], 400);

        if($result == 'penalty')
            return response()->json(['error' => 'penalty', 'message' => 'Patron has a penalty.'], 400);

        if($result == 'maximum-reservation-exceed')
            return response()->json(['error' => 'maximum-reservation-exceed', 'message' => 'Patron exceed the maximum no. of reservation allowed.'], 400);

        if($result == 'already-borrow')
            return response()->json(['error' => 'already-borrow', 'message' => 'Patron already borrow this book.'], 400);

        if($result == 'someone-borrow')
            return response()->json(['error' => 'someone-borrow', 'message' => 'Someone already borrow this book.'], 400);

        if($result == 'already-reserve')
            return response()->json(['error' => 'already-reserve', 'message' => 'Patron already reserve this book.'], 400);

        if($result == 'someone-reserve')
            return response()->json(['error' => 'someone-reserve', 'message' => 'Someone already reserve this book.'], 400);

        DB::beginTransaction();
        try {
            $reservation = $this->reservationService->create($request->except('_token'));
            DB::commit();

            return response()->json([
                'message' => 'Book is successfully reserve.',
                'data'    => new ReservationResource($reservation)
            ], 201);
        }catch (\Exception $exception) {
            DB::rollback();
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function getPatronRecord(PatronNoRequest $request)
    {
        $this->authorize('viewAny', Reservation::class);

        $reservations = $this->reservationService->getPatronReservation($request->patron_no);
        return ReservationResource::collection($reservations);
    }
}
