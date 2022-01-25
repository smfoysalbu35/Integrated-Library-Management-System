<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CloseDateRequest;
use App\Models\CloseDate;
use App\Http\Resources\CloseDateResource;
use App\Repositories\CloseDate\CloseDateRepositoryInterface;

class CloseDateController extends Controller
{
    protected $closeDate;

    public function __construct(CloseDateRepositoryInterface $closeDate)
    {
        $this->closeDate = $closeDate;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', CloseDate::class);

        return view('admin.maintenance.close-date.list');
    }

    public function get()
    {
        $this->authorize('view', CloseDate::class);

        $closeDates = $this->closeDate->get();
        return CloseDateResource::collection($closeDates);
    }

    public function search(Request $request)
    {
        $this->authorize('view', CloseDate::class);

        $closeDates = $this->closeDate->search($request->search);
        return CloseDateResource::collection($closeDates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CloseDateRequest $request)
    {
        $this->authorize('create', CloseDate::class);

        $closeDate = $this->closeDate->create($request->except('_token', '_method'));
        return response()->json([
            'message' => 'Close date is successfully added.',
            'data'    => new CloseDateResource($closeDate)
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
        $this->authorize('view', CloseDate::class);

        $closeDate = $this->closeDate->find($id);
        return new CloseDateResource($closeDate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CloseDateRequest $request, $id)
    {
        $this->authorize('update', CloseDate::class);

        $closeDate = $this->closeDate->update($request->except('_token', '_method'), $id);
        return response()->json([
            'message' => 'Close date is successfully updated.',
            'data'    => new CloseDateResource($closeDate)
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
        $this->authorize('delete', CloseDate::class);

        $closeDate = $this->closeDate->delete($id);
        return response()->json([
            'message' => 'Close date is successfully deleted.',
            'data'    => new CloseDateResource($closeDate)
        ]);
    }
}
