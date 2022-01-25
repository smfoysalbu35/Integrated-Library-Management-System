<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccessionRequest;
use App\Models\Accession;
use App\Http\Resources\AccessionResource;
use App\Repositories\Accession\AccessionRepositoryInterface;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Location\LocationRepositoryInterface;

class AccessionController extends Controller
{
    protected $accession, $book, $location;

    public function __construct(AccessionRepositoryInterface $accession, BookRepositoryInterface $book, LocationRepositoryInterface $location)
    {
        $this->accession = $accession;
        $this->book = $book;
        $this->location = $location;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Accession::class);

        $books = $this->book->get();
        $locations = $this->location->get();
        return view('admin.cataloging.accession.list', compact('books', 'locations'));
    }

    public function get()
    {
        $this->authorize('view', Accession::class);

        $accessions = $this->accession->get();
        return AccessionResource::collection($accessions);
    }

    public function search(Request $request)
    {
        $this->authorize('view', Accession::class);

        $accessions = $this->accession->search($request->search);
        return AccessionResource::collection($accessions);
    }

    public function count()
    {
        $this->authorize('view', Accession::class);

        $count = $this->accession->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccessionRequest $request)
    {
        $this->authorize('create', Accession::class);

        $book = $this->book->find($request->book_id);

        if($book->copy > $this->accession->countBy('book_id', $book->id))
        {
            $accession = $this->accession->create($request->except('_token', '_method'));
            return response()->json([
                'message' => 'Accession is successfully added.',
                'data'    => new AccessionResource($accession)
            ], 201);
        }

        return response()->json(['error' => $book->title . ' is already have ' . $book->copy . ' copy.'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Accession::class);

        $accession = $this->accession->find($id);
        return new AccessionResource($accession);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccessionRequest $request, $id)
    {
        $this->authorize('update', Accession::class);

        $accession = $this->accession->find($id);
        $book = $this->book->find($request->book_id);

        if($accession->book->id == $book->id)
        {
            $accession = $this->accession->update($request->except('_token', '_method'), $id);
            return response()->json([
                'message' => 'Accession is successfully updated.',
                'data'    => new AccessionResource($accession)
            ]);
        }

        if($book->copy > $this->accession->countBy('book_id', $book->id))
        {
            $accession = $this->accession->update($request->except('_token', '_method'), $id);
            return response()->json([
                'message' => 'Accession is successfully updated.',
                'data'    => new AccessionResource($accession)
            ]);
        }

        return response()->json(['error' => $book->title . ' is already have ' . $book->copy . ' copy.'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Accession::class);

        $accession = $this->accession->delete($id);
        return response()->json([
            'message' => 'Accession is successfully deleted.',
            'data'    => new AccessionResource($accession)
        ]);
    }
}
