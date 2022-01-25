<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patron extends Model
{
    use SoftDeletes;

    protected $table = 'patrons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patron_no', 'last_name', 'first_name', 'middle_name', 'contact_no', 'image',
        'house_no', 'street', 'barangay', 'municipality', 'province',
        'patron_type_id', 'section_id'
    ];

    public function borrows()
    {
        return $table->hasMany('App\Models\Borrow');
    }

    public function patron_type()
    {
        return $this->belongsTo('App\Models\PatronType');
    }

    public function patron_accounts()
    {
        return $table->hasMany('App\Models\PatronAccount');
    }

    public function patron_account_logs()
    {
        return $this->hasManyThrough('App\Models\PatronAccountLog', 'App\Models\PatronAccount');
    }

    public function patron_attendance_logs()
    {
        return $table->hasMany('App\Models\PatronAttendanceLog');
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

    public function section()
    {
        return $this->belongsTo('App\Models\Section');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }
}
