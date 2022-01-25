<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatronNoRequest;
use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\PatronAttendanceLog\PatronAttendanceLogRepositoryInterface;
use App\Http\Resources\PatronAttendanceLogResource;

class PatronAttendanceLogController extends Controller
{
    protected $patron, $patronAttendanceLog;

    public function __construct(PatronRepositoryInterface $patron, PatronAttendanceLogRepositoryInterface $patronAttendanceLog)
    {
        $this->patron = $patron;
        $this->patronAttendanceLog = $patronAttendanceLog;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->guard()->check())
            return view('admin.patron-attendance-monitoring.index');

        return view('patron-attendance-monitoring.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatronNoRequest $request)
    {
        $patron = $this->patron->findBy('patron_no', $request->patron_no);

        if($this->patronAttendanceLog->checkPatronAttendanceLog($patron->id))
        {
            $patronAttendanceLog = $this->patronAttendanceLog->logout($patron->id);
            return response()->json([
                'status' => 'logout',
                'data' => new PatronAttendanceLogResource($patronAttendanceLog),
            ]);
        }

        $patronAttendanceLog = $this->patronAttendanceLog->login($patron->id);
        return response()->json([
            'status' => 'login',
            'data' => new PatronAttendanceLogResource($patronAttendanceLog),
        ]);
    }
}
