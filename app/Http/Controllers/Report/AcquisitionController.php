<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\User;
use App\Repositories\Accession\AccessionRepositoryInterface;
use App\Http\Resources\AccessionResource;

class AcquisitionController extends Controller
{
    protected $accession;

    public function __construct(AccessionRepositoryInterface $accession)
    {
        $this->accession = $accession;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return view('admin.report.acquisition.index');
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

        $accessions = $this->accession->getAcquisitionReport($request->except('_token'));
        return AccessionResource::collection($accessions);
    }

    public function print(ReportRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $accessions = $this->accession->getAcquisitionReport($request->except('_token'));
        return view('admin.print-report.acquisition.index')->with(['from' => $request->from, 'to' => $request->to, 'accessions' => $accessions]);
    }
}
