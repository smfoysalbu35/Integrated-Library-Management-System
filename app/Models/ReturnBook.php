<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnBook extends Model
{
    protected $table = 'return_books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'borrow_id', 'patron_id', 'accession_id', 'return_date', 'return_time'
    ];

    public function accession()
    {
        return $this->belongsTo('App\Models\Accession');
    }

    public function borrow()
    {
        return $this->belongsTo('App\Models\Borrow');
    }

    public function patron()
    {
        return $this->belongsTo('App\Models\Patron');
    }

    public function penalties()
    {
        return $this->hasMany('App\Models\Penalty');
    }
}
