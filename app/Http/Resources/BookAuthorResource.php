<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookAuthorResource extends JsonResource
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

            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name,
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
