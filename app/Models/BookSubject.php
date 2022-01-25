<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookSubject extends Model
{
    use SoftDeletes;

    protected $table = 'book_subjects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_id', 'subject_id'
    ];

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    public function subject()
    {
        return $this->belongsTo('App\Models\Subject');
    }
}
