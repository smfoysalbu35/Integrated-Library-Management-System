<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $table = 'books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'place_publication', 'publisher', 'copy_right', 'isbn', 'edition', 'volume', 'call_number', 'copy'
    ];

    public function accessions()
    {
        return $this->hasMany('App\Models\Accession');
    }

    public function authors()
    {
        return $this->belongsToMany('App\Models\Author', 'book_authors');
    }

    public function book_authors()
    {
        return $this->hasMany('App\Models\BookAuthor');
    }

    public function book_subjects()
    {
        return $this->hasMany('App\Models\BookSubject');
    }

    public function borrows()
    {
        return $this->hasManyThrough('App\Models\Borrow', 'App\Models\Accession');
    }

    public function penalties()
    {
        return $this->hasManyThrough('App\Models\Penalty', 'App\Models\Accession');
    }

    public function reservations()
    {
        return $this->hasManyThrough('App\Models\Reservation', 'App\Models\Accession');
    }

    public function return_books()
    {
        return $this->hasManyThrough('App\Models\ReturnBook', 'App\Models\Accession');
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject', 'book_subjects');
    }
}
