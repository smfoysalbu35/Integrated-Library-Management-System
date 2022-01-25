<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookSubjectRequest;
use App\Models\BookSubject;
use App\Http\Resources\BookSubjectResource;
use App\Repositories\BookSubject\BookSubjectRepositoryInterface;

class BookSubjectController extends Controller
{
    protected $bookSubject;

    public function __construct(BookSubjectRepositoryInterface $bookSubject)
    {
        $this->bookSubject = $bookSubject;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', BookSubject::class);

        $bookSubjects = $this->bookSubject->get();
        return BookSubjectResource::collection($bookSubjects);
    }

    public function search(Request $request)
    {
        $this->authorize('view', BookSubject::class);

        $bookSubjects = $this->bookSubject->search($request->search);
        return BookSubjectResource::collection($bookSubjects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookSubjectRequest $request)
    {
        $this->authorize('create', BookSubject::class);

        $bookSubject = $this->bookSubject->create($request->except('_token', '_method'));
        return response()->json([
            'message' => 'Book subject is successfully added.',
            'data'    => new BookSubjectResource($bookSubject)
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
        $this->authorize('view', BookSubject::class);

        $bookSubject = $this->bookSubject->find($id);
        return new BookSubjectResource($bookSubject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookSubjectRequest $request, $id)
    {
        $this->authorize('update', BookSubject::class);

        $bookSubject = $this->bookSubject->update($request->except('_token', '_method'), $id);
        return response()->json([
            'message' => 'Book subject is successfully updated.',
            'data'    => new BookSubjectResource($bookSubject)
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
        $this->authorize('delete', BookSubject::class);

        $bookSubject = $this->bookSubject->delete($id);
        return response()->json([
            'message' => 'Book subject is successfully deleted.',
            'data'    => new BookSubjectResource($bookSubject)
        ]);
    }
}
