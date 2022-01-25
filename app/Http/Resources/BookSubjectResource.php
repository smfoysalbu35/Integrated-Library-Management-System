<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookSubjectResource extends JsonResource
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

            'subject' => [
                'id' => $this->subject->id,
                'name' => $this->subject->name,
            ],

            'book' => [
                'id' => $this->book->id,
                'title' => $this->book->title,
                'call_number' => $this->book->call_number,
                'isbn' => $this->book->isbn,
            ],
        ];
    }
}
