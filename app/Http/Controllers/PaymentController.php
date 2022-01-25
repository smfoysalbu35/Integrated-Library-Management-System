<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Transaction;
use App\Services\Payment\PaymentService;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);

        return view('admin.circulation.payment.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequest $request)
    {
        $this->authorize('create', Transaction::class);

        $result = $this->paymentService->processPaymentRules($request->except('_token'));

        if($result == 'no-penalty')
            return response()->json(['error' => 'no-penalty', 'message' => 'Patron has no penalty.'], 400);

        if($result == 'insufficient-payment')
            return response()->json(['error' => 'insufficient-payment', 'message' => 'Patron payment is insufficient.'], 400);

        DB::beginTransaction();
        try {
            $transaction = $this->paymentService->create($request->except('_token'));
            DB::commit();

            return response()->json([
                'message' => 'Patron penalty payment is successfully settled.',
                'data'    => new TransactionResource($transaction)
            ], 201);
        }catch (\Exception $exception) {
            DB::rollback();
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
