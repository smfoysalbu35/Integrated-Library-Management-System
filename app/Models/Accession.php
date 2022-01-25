<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accession extends Model
{
    use SoftDeletes;

    protected $table = 'accessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'accession_no', 'book_id', 'location_id', 'acquired_date', 'donnor_name', 'price', 'status'
    ];

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    public function borrows()
    {
        return $table->hasMany('App\Models\Borrow');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function penalties()
    {
        return $this->hasMany('App\Models\Penalty');
    }

    public function reservations()
    {
        return $this->hasMany('App\Models\Reservation');
    }

    public function return_books()
    {
        return $this->hasMany('App\Models\ReturnBook');
    }
}
