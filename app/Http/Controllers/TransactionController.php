<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
        $this->authorize('view', Transaction::class);

        $transactions = $this->transaction->get();
        return view('admin.circulation.transaction.list', compact('transactions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Transaction::class);

        $transaction = $this->transaction->find($id);
        return new TransactionResource($transaction);
    }
}
