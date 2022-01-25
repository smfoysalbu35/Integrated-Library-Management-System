<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $table = 'borrows';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patron_id', 'accession_id', 'borrow_date', 'borrow_time', 'status'
    ];

    public function accession()
    {
        return $this->belongsTo('App\Models\Accession');
    }

    public function patron()
    {
        return $this->belongsTo('App\Models\Patron');
    }

    public function return_books()
    {
        return $this->hasMany('App\Models\ReturnBook');
    }
}
