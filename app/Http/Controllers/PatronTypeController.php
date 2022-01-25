<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PatronTypeRequest;
use App\Models\PatronType;
use App\Http\Resources\PatronTypeResource;
use App\Repositories\PatronType\PatronTypeRepositoryInterface;

class PatronTypeController extends Controller
{
    protected $patronType;

    public function __construct(PatronTypeRepositoryInterface $patronType)
    {
        $this->patronType = $patronType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', PatronType::class);

        return view('admin.manage-patron.patron-type.list');
    }

    public function get()
    {
        $this->authorize('view', PatronType::class);

        $patronTypes = $this->patronType->get();
        return PatronTypeResource::collection($patronTypes);
    }

    public function search(Request $request)
    {
        $this->authorize('view', PatronType::class);

        $patronTypes = $this->patronType->search($request->search);
        return PatronTypeResource::collection($patronTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatronTypeRequest $request)
    {
        $this->authorize('create', PatronType::class);

        $patronType = $this->patronType->create($request->except('_token', '_method'));
        return response()->json([
            'message' => 'Patron type is successfully added.',
            'data'    => new PatronTypeResource($patronType)
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
        $this->authorize('view', PatronType::class);

        $patronType = $this->patronType->find($id);
        return new PatronTypeResource($patronType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PatronTypeRequest $request, $id)
    {
        $this->authorize('update', PatronType::class);

        $patronType = $this->patronType->update($request->except('_token', '_method'), $id);
        return response()->json([
            'message' => 'Patron type is successfully updated.',
            'data'    => new PatronTypeResource($patronType)
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
        $this->authorize('delete', PatronType::class);

        $patronType = $this->patronType->delete($id);
        return response()->json([
            'message' => 'Patron type is successfully deleted.',
            'data'    => new PatronTypeResource($patronType)
        ]);
    }
}
