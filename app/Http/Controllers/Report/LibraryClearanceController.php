<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Models\User;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Http\Resources\TransactionResource;

class LibraryClearanceController extends Controller
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
        $this->authorize('viewAny', User::class);

        return view('admin.report.library-clearance.index');
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

        $transactions = $this->transaction->getTransactionReport($request->except('_token'));
        return TransactionResource::collection($transactions);
    }

    public function print(ReportRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $transactions = $this->transaction->getTransactionReport($request->except('_token'));
        return view('admin.print-report.library-clearance.index')->with(['from' => $request->from, 'to' => $request->to, 'transactions' => $transactions]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('viewAny', User::class);

        $transaction = $this->transaction->find($id);
        return new TransactionResource($transaction);
    }

    public function printDetail($id)
    {
        $this->authorize('viewAny', User::class);

        $transaction = $this->transaction->find($id);
        return view('admin.print-report.library-clearance.print-detail', compact('transaction'));
    }
}
