<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatronType extends Model
{
    use SoftDeletes;

    protected $table = 'patron_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'fines', 'no_of_borrow_allowed', 'no_of_day_borrow_allowed', 'no_of_reserve_allowed', 'no_of_day_reserve_allowed'
    ];

    public function borrows()
    {
        return $this->hasManyThrough('App\Models\Borrow', 'App\Models\Patron');
    }

    public function patrons()
    {
        return $this->hasMany('App\Models\Patron');
    }

    public function penalties()
    {
        return $this->hasManyThrough('App\Models\Penalty', 'App\Models\Patron');
    }

    public function reservations()
    {
        return $this->hasManyThrough('App\Models\Reservation', 'App\Models\Patron');
    }

    public function return_books()
    {
        return $this->hasManyThrough('App\Models\ReturnBook', 'App\Models\Patron');
    }

    public function transactions()
    {
        return $this->hasManyThrough('App\Models\Transaction', 'App\Models\Patron');
    }
}
