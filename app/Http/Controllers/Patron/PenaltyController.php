<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use App\Repositories\Penalty\PenaltyRepositoryInterface;

class PenaltyController extends Controller
{
    protected $penalty;

    public function __construct(PenaltyRepositoryInterface $penalty)
    {
        $this->penalty = $penalty;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penalties = $this->penalty->getBy('patron_id', auth()->guard('patron')->user()->id);
        return view('patron-web.penalty.index', compact('penalties'));
    }
}
