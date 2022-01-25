<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LocationRequest;
use App\Models\Location;
use App\Http\Resources\LocationResource;
use App\Repositories\Location\LocationRepositoryInterface;

class LocationController extends Controller
{
    protected $location;

    public function __construct(LocationRepositoryInterface $location)
    {
        $this->location = $location;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Location::class);

        return view('admin.maintenance.location.list');
    }

    public function get()
    {
        $this->authorize('view', Location::class);

        $locations = $this->location->get();
        return LocationResource::collection($locations);
    }

    public function search(Request $request)
    {
        $this->authorize('view', Location::class);

        $locations = $this->location->search($request->search);
        return LocationResource::collection($locations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
        $this->authorize('create', Location::class);

        $location = $this->location->create($request->except('_token', '_method'));
        return response()->json([
            'message' => 'Location is successfully added.',
            'data'    => new LocationResource($location)
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
        $this->authorize('view', Location::class);

        $location = $this->location->find($id);
        return new LocationResource($location);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LocationRequest $request, $id)
    {
        $this->authorize('update', Location::class);

        $location = $this->location->update($request->except('_token', '_method'), $id);
        return response()->json([
            'message' => 'Location is successfully updated.',
            'data'    => new LocationResource($location)
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
        $this->authorize('delete', Location::class);

        $location = $this->location->delete($id);
        return response()->json([
            'message' => 'Location is successfully deleted.',
            'data'    => new LocationResource($location)
        ]);
    }
}
