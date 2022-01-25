<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PenaltyResource extends JsonResource
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

            'return_book' => [
                'id' => $this->return_book->id,
                'return_date' => $this->return_book->return_date,
                'return_time' => $this->return_book->return_time,
            ],

            'borrow' => [
                'id' => $this->return_book->borrow->id,
                'borrow_date' => $this->return_book->borrow->borrow_date,
                'borrow_time' => $this->return_book->borrow->borrow_time,
                'status' => $this->return_book->borrow->status,
            ],

            'patron' => [
                'id' => $this->patron->id,
                'patron_no' => $this->patron->patron_no,
                'name' => "{$this->patron->first_name} {$this->patron->last_name}",
            ],

            'accession' => [
                'id' => $this->accession->id,
                'accession_no' => $this->accession->accession_no,
                'status' => $this->accession->status,
            ],

            'book' => [
                'id' => $this->accession->book->id,
                'title' => $this->accession->book->title,
                'call_number' => $this->accession->book->call_number,
                'isbn' => $this->accession->book->isbn,
            ],

            'penalty_due_date' => $this->penalty_due_date,
            'amount' => $this->amount,
            'overdue' => $this->overdue,
            'status' => $this->status,
        ];
    }
}
