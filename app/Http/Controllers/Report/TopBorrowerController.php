<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatronReportRequest;
use App\Models\User;
use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\PatronType\PatronTypeRepositoryInterface;

class TopBorrowerController extends Controller
{
    protected $patron, $patronType;

    public function __construct(PatronRepositoryInterface $patron, PatronTypeRepositoryInterface $patronType)
    {
        $this->patron = $patron;
        $this->patronType = $patronType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $patronTypes = $this->patronType->get();
        return view('admin.report.top-borrower.index', compact('patronTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatronReportRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $patronType = $this->patronType->find($request->patron_type_id);
        $topBorrowers = $this->patron->getTopBorrowerReport($request->except('_token'));

        return response()->json([
            'from' => $request->from,
            'to' => $request->to,
            'patronType' => $patronType,
            'topBorrowers' => $topBorrowers
        ]);
    }

    public function print(PatronReportRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $patronType = $this->patronType->find($request->patron_type_id);
        $topBorrowers = $this->patron->getTopBorrowerReport($request->except('_token'));

        return view('admin.print-report.top-borrower.index')->with(['from' => $request->from, 'to' => $request->to, 'patronType' => $patronType, 'topBorrowers' => $topBorrowers]);
    }
}
