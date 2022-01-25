<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatronAttendanceLog extends Model
{
    protected $table = 'patron_attendance_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patron_id', 'date_in', 'time_in', 'date_out', 'time_out', 'status'
    ];

    public function patron()
    {
        return $this->belongsTo('App\Models\Patron');
    }
}
