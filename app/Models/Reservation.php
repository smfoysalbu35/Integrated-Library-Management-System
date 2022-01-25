<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patron_id', 'accession_id', 'reservation_date', 'reservation_time', 'reservation_end_date'
    ];

    public function accession()
    {
        return $this->belongsTo('App\Models\Accession');
    }

    public function patron()
    {
        return $this->belongsTo('App\Models\Patron');
    }
}
