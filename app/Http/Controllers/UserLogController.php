<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use App\Repositories\UserLog\UserLogRepositoryInterface;

class UserLogController extends Controller
{
    protected $userLog;

    public function __construct(UserLogRepositoryInterface $userLog)
    {
        $this->userLog = $userLog;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', UserLog::class);

        $userLogs = $this->userLog->get();
        return view('admin.logs.user-logs.list', compact('userLogs'));
    }
}
