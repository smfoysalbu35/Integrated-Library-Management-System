<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'title' => $this->title,
            'call_number' => $this->call_number,
            'isbn' => $this->isbn,

            'edition' => $this->edition,
            'volume' => $this->volume,

            'publisher' => $this->publisher,
            'place_publication' => $this->place_publication,

            'copy_right' => $this->copy_right,
            'copy' => $this->copy,
        ];
    }
}
