<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatronNoRequest;
use App\Models\Penalty;
use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\Penalty\PenaltyRepositoryInterface;
use App\Http\Resources\PenaltyResource;

class PenaltyController extends Controller
{
    protected $patron, $penalty;

    public function __construct(PatronRepositoryInterface $patron, PenaltyRepositoryInterface $penalty)
    {
        $this->patron = $patron;
        $this->penalty = $penalty;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Penalty::class);

        $penalties = $this->penalty->get();
        return view('admin.circulation.penalty.list', compact('penalties'));
    }

    public function getPatronRecord(PatronNoRequest $request)
    {
        $this->authorize('viewAny', Penalty::class);

        $patron = $this->patron->findBy('patron_no', $request->patron_no);

        $penalties = $this->penalty->getPenaltyByPatronId($patron->id);
        $totalPenalty = $this->penalty->findTotalPenaltyByPatronId($patron->id);

        return response()->json([
            'penalties' => PenaltyResource::collection($penalties),
            'total_penalty' => isset($totalPenalty) ? $totalPenalty : 0
        ]);
    }
}
