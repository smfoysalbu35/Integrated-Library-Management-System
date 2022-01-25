<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    protected $table = 'authors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany('App\Models\Book', 'book_authors');
    }

    public function book_authors()
    {
        return $this->hasMany('App\Models\BookAuthor');
    }
}
