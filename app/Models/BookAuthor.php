<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookAuthor extends Model
{
    use SoftDeletes;

    protected $table = 'book_authors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_id', 'author_id'
    ];

    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }
}
