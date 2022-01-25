<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccessionNoRequest;
use App\Services\Reservation\PatronAccountReservationService;
use App\Http\Resources\ReservationResource;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $reservationService;

    public function __construct(PatronAccountReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        $accessions = $this->reservationService->getAccession();
        return view('patron-web.home', compact('accessions'));
    }

    public function list()
    {
        $reservations = $this->reservationService->getReservationByPatronId();
        return ReservationResource::collection($reservations);
    }

    public function store(AccessionNoRequest $request)
    {
        $result = $this->reservationService->processReservationRules($request->accession_no);

        if($result == 'penalty')
            return response()->json(['error' => 'penalty', 'message' => 'You have a penalty.'], 400);

        if($result == 'maximum-reservation-exceed')
            return response()->json(['error' => 'maximum-reservation-exceed', 'message' => 'You already exceed the maximum no. of reservation allowed.'], 400);

        if($result == 'already-borrow')
            return response()->json(['error' => 'already-borrow', 'message' => 'You already borrow this book.'], 400);

        if($result == 'someone-borrow')
            return response()->json(['error' => 'someone-borrow', 'message' => 'Someone already borrow this book.'], 400);

        if($result == 'already-reserve')
            return response()->json(['error' => 'already-reserve', 'message' => 'You already reserve this book.'], 400);

        if($result == 'someone-reserve')
            return response()->json(['error' => 'someone-reserve', 'message' => 'Someone already reserve this book.'], 400);

        DB::beginTransaction();
        try {
            $reservation = $this->reservationService->create($request->accession_no);
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
}
