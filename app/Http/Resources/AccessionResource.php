<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccessionResource extends JsonResource
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
            'accession_no' => $this->accession_no,

            'book' => [
                'id' => $this->book->id,
                'title' => $this->book->title,
                'call_number' => $this->book->call_number,
                'isbn' => $this->book->isbn,
                'edition' => $this->book->edition,
                'volume' => $this->book->volume,
            ],

            'location' => [
                'id' => $this->location->id,
                'name' => $this->location->name,
                'symbol' => $this->location->symbol,
                'allowed' => $this->location->allowed,
            ],

            'acquired_date' => $this->acquired_date,
            'donnor_name' => $this->donnor_name,
            'price' => $this->price,
            'status' => $this->status,
        ];
    }
}
