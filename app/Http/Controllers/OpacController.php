<?php

namespace App\Http\Controllers;

use App\Repositories\Accession\AccessionRepositoryInterface;

class OpacController extends Controller
{
    protected $accession;

    public function __construct(AccessionRepositoryInterface $accession)
    {
        $this->accession = $accession;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accessions = $this->accession->get();

        if(auth()->guard()->check())
            return view('admin.opac.index', compact('accessions'));

        return view('opac.index', compact('accessions'));
    }
}
