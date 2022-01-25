<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'patron' => [
                'id' => $this->patron->id,
                'patron_no' => $this->patron->patron_no,
                'name' => "{$this->patron->first_name} {$this->patron->last_name}",
            ],

            'user' => [
                'id' => $this->user->id,
                'name' => "{$this->user->first_name} {$this->user->last_name}",
                'email' => $this->user->email,
            ],

            'transaction_date' => $this->transaction_date,
            'transaction_time' => $this->transaction_time,
            'total_penalty' => $this->total_penalty,
            'payment' => $this->payment,
            'change' => $this->change,

            'transaction_details' => $this->transaction_details,
        ];
    }
}
