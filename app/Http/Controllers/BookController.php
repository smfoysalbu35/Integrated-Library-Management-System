<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Repositories\Book\BookRepositoryInterface;

class BookController extends Controller
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
        $this->authorize('view', Book::class);

        return view('admin.cataloging.book.list');
    }

    public function get()
    {
        $this->authorize('view', Book::class);

        $books = $this->book->get();
        return BookResource::collection($books);
    }

    public function search(Request $request)
    {
        $this->authorize('view', Book::class);

        $books = $this->book->search($request->search);
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        $this->authorize('create', Book::class);

        $book = $this->book->create($request->except('_token', '_method'));
        return response()->json([
            'message' => 'Book is successfully added.',
            'data'    => new BookResource($book)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Book::class);

        $book = $this->book->find($id);
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, $id)
    {
        $this->authorize('update', Book::class);

        $book = $this->book->update($request->except('_token', '_method'), $id);
        return response()->json([
            'message' => 'Book is successfully updated.',
            'data'    => new BookResource($book)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Book::class);

        $book = $this->book->delete($id);
        return response()->json([
            'message' => 'Book is successfully deleted.',
            'data'    => new BookResource($book)
        ]);
    }
}
