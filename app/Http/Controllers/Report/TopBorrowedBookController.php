<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\User;
use App\Repositories\Book\BookRepositoryInterface;

class TopBorrowedBookController extends Controller
{
    protected $book;

    public function __construct(BookRepositoryInterface $book)
    {
        $this->book = $book;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return view('admin.report.top-borrowed-book.index');
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

        $topBorrowedBooks = $this->book->getTopBorrowedBookReport($request->except('_token'));
        return response()->json([
            'from' => $request->from,
            'to' => $request->to,
            'topBorrowedBooks' => $topBorrowedBooks
        ]);
    }

    public function print(ReportRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $topBorrowedBooks = $this->book->getTopBorrowedBookReport($request->except('_token'));
        return view('admin.print-report.top-borrowed-book.index')->with(['from' => $request->from, 'to' => $request->to, 'topBorrowedBooks' => $topBorrowedBooks]);
    }
}
