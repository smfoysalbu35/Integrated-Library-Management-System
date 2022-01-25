<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $table = 'penalties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'return_book_id', 'patron_id', 'accession_id', 'penalty_due_date', 'amount', 'overdue', 'status'
    ];

    public function accession()
    {
        return $this->belongsTo('App\Models\Accession');
    }

    public function patron()
    {
        return $this->belongsTo('App\Models\Patron');
    }

    public function return_book()
    {
        return $this->belongsTo('App\Models\ReturnBook');
    }
}
