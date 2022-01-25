<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id', 'accession_id', 'penalty_id'
    ];

    public function accession()
    {
        return $this->belongsTo('App\Models\Accession');
    }

    public function penalty()
    {
        return $this->belongsTo('App\Models\Penalty');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction');
    }
}
