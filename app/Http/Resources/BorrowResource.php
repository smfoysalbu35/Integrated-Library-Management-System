<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BorrowResource extends JsonResource
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

            'borrow_date' => $this->borrow_date,
            'borrow_time' => $this->borrow_time,
            'status' => $this->status,
        ];
    }
}
