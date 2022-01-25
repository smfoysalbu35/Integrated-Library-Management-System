<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatronRequest;
use App\Models\Patron;
use App\Repositories\Patron\PatronRepositoryInterface;
use App\Repositories\PatronType\PatronTypeRepositoryInterface;
use App\Repositories\Section\SectionRepositoryInterface;

class PatronController extends Controller
{
    protected $patron, $patronType, $section;

    public function __construct(PatronRepositoryInterface $patron, PatronTypeRepositoryInterface $patronType, SectionRepositoryInterface $section)
    {
        $this->patron = $patron;
        $this->patronType = $patronType;
        $this->section = $section;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Patron::class);

        $patrons = $this->patron->get();
        return view('admin.manage-patron.patron.list', compact('patrons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Patron::class);

        $patronNo = $this->patron->createPatronNo();
        $patronTypes = $this->patronType->get();
        $sections = $this->section->get();
        return view('admin.manage-patron.patron.create', compact('patronNo', 'patronTypes', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatronRequest $request)
    {
        $this->authorize('create', Patron::class);

        $patron = $this->patron->create($request);
        return redirect()->route('patrons.create')->with(['message' => 'Patron is successfully added.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('view', Patron::class);

        $patron = $this->patron->find($id);
        $patronTypes = $this->patronType->get();
        $sections = $this->section->get();
        return view('admin.manage-patron.patron.edit', compact('patron', 'patronTypes', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PatronRequest $request, $id)
    {
        $this->authorize('update', Patron::class);

        $patron = $this->patron->update($request, $id);
        return redirect()->route('patrons.edit', ['patron' => $id])->with(['message' => 'Patron is successfully updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Patron::class);

        $patron = $this->patron->delete($id);
        return redirect()->route('patrons.index')->with(['message' => 'Patron is successfully deleted.']);
    }
}
