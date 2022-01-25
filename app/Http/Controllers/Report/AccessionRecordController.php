<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Accession\AccessionRepositoryInterface;

class AccessionRecordController extends Controller
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

        $accessions = $this->accession->get();
        return view('admin.report.accession-record.index', compact('accessions'));
    }

    public function print()
    {
        $this->authorize('viewAny', User::class);

        $accessions = $this->accession->get();
        return view('admin.print-report.accession-record.index', compact('accessions'));
    }
}
