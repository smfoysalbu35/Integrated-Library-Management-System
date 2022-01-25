<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use App\Repositories\Borrow\BorrowRepositoryInterface;

class BorrowController extends Controller
{
    protected $borrow;

    public function __construct(BorrowRepositoryInterface $borrow)
    {
        $this->borrow = $borrow;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $borrows = $this->borrow->getBy('patron_id', auth()->guard('patron')->user()->id);
        return view('patron-web.borrow.index', compact('borrows'));
    }
}
