<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\User;
use App\Repositories\Penalty\PenaltyRepositoryInterface;
use App\Http\Resources\PenaltyResource;

class UnpaidPenaltyController extends Controller
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
        $this->authorize('viewAny', User::class);

        return view('admin.report.unpaid-penalty.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $unpaidPenalties = $this->penalty->getUnpaidPenaltyReport($request->except('_token'));
        return PenaltyResource::collection($unpaidPenalties);
    }

    public function print(ReportRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $unpaidPenalties = $this->penalty->getUnpaidPenaltyReport($request->except('_token'));
        return view('admin.print-report.unpaid-penalty.index')->with(['from' => $request->from, 'to' => $request->to, 'unpaidPenalties' => $unpaidPenalties]);
    }
}
