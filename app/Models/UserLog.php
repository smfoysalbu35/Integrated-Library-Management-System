<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $table = 'user_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'date_in', 'time_in', 'date_out', 'time_out', 'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
