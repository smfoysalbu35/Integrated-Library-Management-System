<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GradeLevelRequest;
use App\Models\GradeLevel;
use App\Http\Resources\GradeLevelResource;
use App\Repositories\GradeLevel\GradeLevelRepositoryInterface;

class GradeLevelController extends Controller
{
    protected $gradeLevel;

    public function __construct(GradeLevelRepositoryInterface $gradeLevel)
    {
        $this->gradeLevel = $gradeLevel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', GradeLevel::class);

        return view('admin.maintenance.grade-level.list');
    }

    public function get()
    {
        $this->authorize('view', GradeLevel::class);

        $gradeLevels = $this->gradeLevel->get();
        return GradeLevelResource::collection($gradeLevels);
    }

    public function search(Request $request)
    {
        $this->authorize('view', GradeLevel::class);

        $gradeLevels = $this->gradeLevel->search($request->search);
        return GradeLevelResource::collection($gradeLevels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GradeLevelRequest $request)
    {
        $this->authorize('create', GradeLevel::class);

        $gradeLevel = $this->gradeLevel->create($request->except('_token', '_method'));
        return response()->json([
            'message' => 'Grade level is successfully added.',
            'data'    => new GradeLevelResource($gradeLevel)
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
        $this->authorize('view', GradeLevel::class);

        $gradeLevel = $this->gradeLevel->find($id);
        return new GradeLevelResource($gradeLevel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GradeLevelRequest $request, $id)
    {
        $this->authorize('update', GradeLevel::class);

        $gradeLevel = $this->gradeLevel->update($request->except('_token', '_method'), $id);
        return response()->json([
            'message' => 'Grade level is successfully updated.',
            'data'    => new GradeLevelResource($gradeLevel)
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
        $this->authorize('delete', GradeLevel::class);

        $gradeLevel = $this->gradeLevel->delete($id);
        return response()->json([
            'message' => 'Grade level is successfully deleted.',
            'data'    => new GradeLevelResource($gradeLevel)
        ]);
    }
}
