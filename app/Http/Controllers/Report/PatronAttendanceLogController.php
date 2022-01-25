<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\User;
use App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepositoryInterface;
use App\Http\Resources\PatronAttendanceLogResource;

class PatronAttendanceLogController extends Controller
{
    protected $patronAttendanceLog;

    public function __construct(PatronAttendanceLogRepositoryInterface $patronAttendanceLog)
    {
        $this->patronAttendanceLog = $patronAttendanceLog;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return view('admin.report.patron-attendance-monitoring.index');
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

        $patronAttendanceLogs = $this->patronAttendanceLog->getPatronAttendanceLogReport($request->except('_token'));
        return PatronAttendanceLogResource::collection($patronAttendanceLogs);
    }

    public function print(ReportRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $patronAttendanceLogs = $this->patronAttendanceLog->getPatronAttendanceLogReport($request->except('_token'));
        return view('admin.print-report.patron-attendance-monitoring.index')->with(['from' => $request->from, 'to' => $request->to, 'patronAttendanceLogs' => $patronAttendanceLogs]);
    }
}
