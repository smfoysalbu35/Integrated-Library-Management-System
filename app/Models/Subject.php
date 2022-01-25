<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $table = 'subjects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany('App\Models\Book', 'book_subjects');
    }

    public function book_subjects()
    {
        return $this->hasMany('App\Models\BookSubject');
    }
}
