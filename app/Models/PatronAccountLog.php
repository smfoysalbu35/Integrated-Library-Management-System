<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatronAccountLog extends Model
{
    protected $table = 'patron_account_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patron_account_id', 'date_in', 'time_in', 'date_out', 'time_out', 'status'
    ];

    public function patron_account()
    {
        return $this->belongsTo('App\Models\PatronAccount');
    }
}
