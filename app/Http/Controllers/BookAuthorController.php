<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookAuthorRequest;
use App\Models\BookAuthor;
use App\Http\Resources\BookAuthorResource;
use App\Repositories\BookAuthor\BookAuthorRepositoryInterface;

class BookAuthorController extends Controller
{
    protected $bookAuthor;

    public function __construct(BookAuthorRepositoryInterface $bookAuthor)
    {
        $this->bookAuthor = $bookAuthor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', BookAuthor::class);

        $bookAuthors = $this->bookAuthor->get();
        return BookAuthorResource::collection($bookAuthors);
    }

    public function search(Request $request)
    {
        $this->authorize('view', BookAuthor::class);

        $bookAuthors = $this->bookAuthor->search($request->search);
        return BookAuthorResource::collection($bookAuthors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookAuthorRequest $request)
    {
        $this->authorize('create', BookAuthor::class);

        $bookAuthor = $this->bookAuthor->create($request->except('_token', '_method'));
        return response()->json([
            'message' => 'Book author is successfully added.',
            'data'    => new BookAuthorResource($bookAuthor)
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
        $this->authorize('view', BookAuthor::class);

        $bookAuthor = $this->bookAuthor->find($id);
        return new BookAuthorResource($bookAuthor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookAuthorRequest $request, $id)
    {
        $this->authorize('update', BookAuthor::class);

        $bookAuthor = $this->bookAuthor->update($request->except('_token', '_method'), $id);
        return response()->json([
            'message' => 'Book author is successfully updated.',
            'data'    => new BookAuthorResource($bookAuthor)
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
        $this->authorize('delete', BookAuthor::class);

        $bookAuthor = $this->bookAuthor->delete($id);
        return response()->json([
            'message' => 'Book author is successfully deleted.',
            'data'    => new BookAuthorResource($bookAuthor)
        ]);
    }
}
