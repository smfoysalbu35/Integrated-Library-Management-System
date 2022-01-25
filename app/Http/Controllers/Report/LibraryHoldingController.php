<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Book\BookRepositoryInterface;

class LibraryHoldingController extends Controller
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

        $books = $this->book->get();
        return view('admin.report.library-holding.index', compact('books'));
    }

    public function print()
    {
        $this->authorize('viewAny', User::class);

        $books = $this->book->get();
        return view('admin.print-report.library-holding.index', compact('books'));
    }
}
