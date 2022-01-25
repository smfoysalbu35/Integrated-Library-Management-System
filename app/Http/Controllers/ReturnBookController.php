<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReturnBookRequest;
use App\Models\ReturnBook;
use App\Services\ReturnBook\ReturnBookService;
use App\Http\Resources\ReturnBookResource;
use Illuminate\Support\Facades\DB;

class ReturnBookController extends Controller
{
    protected $returnBookService;

    public function __construct(ReturnBookService $returnBookService)
    {
        $this->returnBookService = $returnBookService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', ReturnBook::class);

        $returnBooks = $this->returnBookService->get();
        return view('admin.circulation.return-book.list', compact('returnBooks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', ReturnBook::class);

        return view('admin.circulation.return-book.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReturnBookRequest $request)
    {
        $this->authorize('create', ReturnBook::class);

        $result = $this->returnBookService->processReturningBookRules($request->except('_token'));

        if($result == 'patron-not-login')
            return response()->json(['error' => 'patron-not-login', 'message' => 'Patron doesn\'t login in the library.'], 400);

        if($result == 'someone-borrow')
            return response()->json(['error' => 'someone-borrow', 'message' => 'Patron didn\'t borrow this book.'], 400);

        DB::beginTransaction();
        try {
            $returnBook = $this->returnBookService->create($request->except('_token'));
            DB::commit();

            return response()->json([
                'message' => 'Book is successfully returned.',
                'data'    => new ReturnBookResource($returnBook)
            ], 201);
        }catch (\Exception $exception) {
            DB::rollback();
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
