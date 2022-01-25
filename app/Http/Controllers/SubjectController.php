<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use App\Http\Resources\SubjectResource;
use App\Repositories\Subject\SubjectRepositoryInterface;
use App\Repositories\Book\BookRepositoryInterface;

class SubjectController extends Controller
{
    protected $book, $subject;

    public function __construct(BookRepositoryInterface $book, SubjectRepositoryInterface $subject)
    {
        $this->book = $book;
        $this->subject = $subject;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Subject::class);

        $books = $this->book->get();
        $subjects = $this->subject->get();
        return view('admin.cataloging.subject.list', compact('books', 'subjects'));
    }

    public function get()
    {
        $this->authorize('view', Subject::class);

        $subjects = $this->subject->get();
        return SubjectResource::collection($subjects);
    }

    public function search(Request $request)
    {
        $this->authorize('view', Subject::class);

        $subjects = $this->subject->search($request->search);
        return SubjectResource::collection($subjects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectRequest $request)
    {
        $this->authorize('create', Subject::class);

        $subject = $this->subject->create($request->except('_token', '_method'));
        return response()->json([
            'message' => 'Subject is successfully added.',
            'data'    => new SubjectResource($subject)
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
        $this->authorize('view', Subject::class);

        $subject = $this->subject->find($id);
        return new SubjectResource($subject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubjectRequest $request, $id)
    {
        $this->authorize('update', Subject::class);

        $subject = $this->subject->update($request->except('_token', '_method'), $id);
        return response()->json([
            'message' => 'Subject is successfully updated.',
            'data'    => new SubjectResource($subject)
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
        $this->authorize('delete', Subject::class);

        $subject = $this->subject->delete($id);
        return response()->json([
            'message' => 'Subject is successfully deleted.',
            'data'    => new SubjectResource($subject)
        ]);
    }
}
