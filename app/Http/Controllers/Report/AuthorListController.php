<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Author\AuthorRepositoryInterface;

class AuthorListController extends Controller
{
    protected $author;

    public function __construct(AuthorRepositoryInterface $author)
    {
        $this->author = $author;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $authors = $this->author->get();
        return view('admin.report.author-list.index', compact('authors'));
    }

    public function print()
    {
        $this->authorize('viewAny', User::class);

        $authors = $this->author->get();
        return view('admin.print-report.author-list.index', compact('authors'));
    }
}
