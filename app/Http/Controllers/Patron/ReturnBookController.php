<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use App\Repositories\ReturnBook\ReturnBookRepositoryInterface;

class ReturnBookController extends Controller
{
    protected $returnBook;

    public function __construct(ReturnBookRepositoryInterface $returnBook)
    {
        $this->returnBook = $returnBook;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $returnBooks = $this->returnBook->getBy('patron_id', auth()->guard('patron')->user()->id);
        return view('patron-web.return-book.index', compact('returnBooks'));
    }
}
