<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SectionRequest;
use App\Models\Section;
use App\Http\Resources\SectionResource;
use App\Repositories\Section\SectionRepositoryInterface;
use App\Repositories\GradeLevel\GradeLevelRepositoryInterface;

class SectionController extends Controller
{
    protected $gradeLevel, $section;

    public function __construct(GradeLevelRepositoryInterface $gradeLevel, SectionRepositoryInterface $section)
    {
        $this->section = $section;
        $this->gradeLevel = $gradeLevel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Section::class);

        $gradeLevels = $this->gradeLevel->get();
        return view('admin.maintenance.section.list', compact('gradeLevels'));
    }

    public function get()
    {
        $this->authorize('view', Section::class);

        $sections = $this->section->get();
        return SectionResource::collection($sections);
    }

    public function search(Request $request)
    {
        $this->authorize('view', Section::class);

        $sections = $this->section->search($request->search);
        return SectionResource::collection($sections);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionRequest $request)
    {
        $this->authorize('create', Section::class);

        $section = $this->section->create($request->except('_token', '_method'));
        return response()->json([
            'message' => 'Section is successfully added.',
            'data'    => new SectionResource($section)
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
        $this->authorize('view', Section::class);

        $section = $this->section->find($id);
        return new SectionResource($section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SectionRequest $request, $id)
    {
        $this->authorize('update', Section::class);

        $section = $this->section->update($request->except('_token', '_method'), $id);
        return response()->json([
            'message' => 'Section is successfully updated.',
            'data'    => new SectionResource($section)
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
        $this->authorize('delete', Section::class);

        $section = $this->section->delete($id);
        return response()->json([
            'message' => 'Section is successfully deleted.',
            'data'    => new SectionResource($section)
        ]);
    }
}
