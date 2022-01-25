<?php

namespace App\Http\Controllers;

use App\Models\PatronAccountLog;
use App\Repositories\PatronAccountLog\PatronAccountLogRepositoryInterface;

class PatronAccountLogController extends Controller
{
    protected $patronAccountLog;

    public function __construct(PatronAccountLogRepositoryInterface $patronAccountLog)
    {
        $this->patronAccountLog = $patronAccountLog;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', PatronAccountLog::class);

        $patronAccountLogs = $this->patronAccountLog->get();
        return view('admin.logs.patron-account-logs.index', compact('patronAccountLogs'));
    }
}
