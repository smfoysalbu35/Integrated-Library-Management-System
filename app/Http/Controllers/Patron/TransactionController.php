<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    protected $transaction;

    public function __construct(TransactionRepositoryInterface $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = $this->transaction->getBy('patron_id', auth()->guard('patron')->user()->id);
        return view('patron-web.transaction.index', compact('transactions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = $this->transaction->find($id);
        return new TransactionResource($transaction);
    }
}
