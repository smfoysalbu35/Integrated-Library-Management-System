<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use App\Http\Resources\AuthorResource;
use App\Repositories\Author\AuthorRepositoryInterface;
use App\Repositories\Book\BookRepositoryInterface;

class AuthorController extends Controller
{
    protected $author, $book;

    public function __construct(AuthorRepositoryInterface $author, BookRepositoryInterface $book)
    {
        $this->author = $author;
        $this->book = $book;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Author::class);

        $authors = $this->author->get();
        $books = $this->book->get();
        return view('admin.cataloging.author.list', compact('authors', 'books'));
    }

    public function get()
    {
        $this->authorize('view', Author::class);

        $authors = $this->author->get();
        return AuthorResource::collection($authors);
    }

    public function search(Request $request)
    {
        $this->authorize('view', Author::class);

        $authors = $this->author->search($request->search);
        return AuthorResource::collection($authors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorRequest $request)
    {
        $this->authorize('create', Author::class);

        $author = $this->author->create($request->except('_token', '_method'));
        return response()->json([
            'message' => 'Author is successfully added.',
            'data'    => new AuthorResource($author)
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
        $this->authorize('view', Author::class);

        $author = $this->author->find($id);
        return new AuthorResource($author);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorRequest $request, $id)
    {
        $this->authorize('update', Author::class);

        $author = $this->author->update($request->except('_token', '_method'), $id);
        return response()->json([
            'message' => 'Author is successfully updated.',
            'data'    => new AuthorResource($author)
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
        $this->authorize('delete', Author::class);

        $author = $this->author->delete($id);
        return response()->json([
            'message' => 'Author is successfully deleted.',
            'data'    => new AuthorResource($author)
        ]);
    }
}
