<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowRequest;
use App\Http\Requests\PatronNoRequest;
use App\Models\Borrow;
use App\Services\Borrow\BorrowService;
use App\Http\Resources\BorrowResource;
use App\Http\Resources\PenaltyResource;
use Illuminate\Support\Facades\DB;

class BorrowController extends Controller
{
    protected $borrowService;

    public function __construct(BorrowService $borrowService)
    {
        $this->borrowService = $borrowService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Borrow::class);

        $borrows = $this->borrowService->get();
        return view('admin.circulation.borrow.list', compact('borrows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Borrow::class);

        return view('admin.circulation.borrow.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BorrowRequest $request)
    {
        $this->authorize('create', Borrow::class);

        $result = $this->borrowService->processBorrowingRules($request->except('_token'));

        if($result == 'patron-not-login')
            return response()->json(['error' => 'patron-not-login', 'message' => 'Patron doesn\'t login in the library.'], 400);

        if($result == 'penalty')
            return response()->json(['error' => 'penalty', 'message' => 'Patron has a penalty.'], 400);

        if($result == 'maximum-borrow-exceed')
            return response()->json(['error' => 'maximum-borrow-exceed', 'message' => 'Patron exceed the maximum no. of borrow allowed.'], 400);

        if($result == 'already-borrow')
            return response()->json(['error' => 'already-borrow', 'message' => 'Patron already borrow this book.'], 400);

        if($result == 'someone-borrow')
            return response()->json(['error' => 'someone-borrow', 'message' => 'Someone already borrow this book.'], 400);

        if($result == 'someone-reserve')
            return response()->json(['error' => 'someone-reserve', 'message' => 'Someone already reserve this book.'], 400);

        DB::beginTransaction();
        try {
            $borrow = $this->borrowService->create($request->except('_token'));
            DB::commit();

            return response()->json([
                'message' => 'Book is successfully borrowed.',
                'data'    => new BorrowResource($borrow)
            ], 201);
        }catch(\Exception $exception) {
            DB::rollback();
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function getPatronRecord(PatronNoRequest $request)
    {
        $this->authorize('viewAny', Borrow::class);

        $borrows = $this->borrowService->getPatronBorrow($request->patron_no);
        $penalties = $this->borrowService->getPatronPenalty($request->patron_no);

        return response()->json([
            'borrows' => BorrowResource::collection($borrows),
            'penalties' => PenaltyResource::collection($penalties),
        ]);
    }
}
