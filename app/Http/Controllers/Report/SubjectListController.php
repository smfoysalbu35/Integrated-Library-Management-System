<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Subject\SubjectRepositoryInterface;

class SubjectListController extends Controller
{
    protected $subject;

    public function __construct(SubjectRepositoryInterface $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $subjects = $this->subject->get();
        return view('admin.report.subject-list.index', compact('subjects'));
    }

    public function print()
    {
        $this->authorize('viewAny', User::class);

        $subjects = $this->subject->get();
        return view('admin.print-report.subject-list.index', compact('subjects'));
    }
}
