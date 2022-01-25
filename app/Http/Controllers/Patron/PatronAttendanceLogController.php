<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepositoryInterface;

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
        $patronAttendanceLogs = $this->patronAttendanceLog->getBy('patron_id', auth()->guard('patron')->user()->id);
        return view('patron-web.patron-attendance-monitoring.index', compact('patronAttendanceLogs'));
    }
}
