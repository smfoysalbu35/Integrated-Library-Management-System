<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patron_id', 'user_id', 'transaction_date', 'transaction_time', 'total_penalty', 'payment', 'change'
    ];

    public function patron()
    {
        return $this->belongsTo('App\Models\Patron');
    }

    public function transaction_details()
    {
        return $this->hasMany('App\Models\TransactionDetail');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
