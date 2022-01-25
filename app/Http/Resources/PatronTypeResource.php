<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatronTypeResource extends JsonResource
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
            'name' => $this->name,
            'fines' => $this->fines,
            'no_of_borrow_allowed' => $this->no_of_borrow_allowed,
            'no_of_day_borrow_allowed' => $this->no_of_day_borrow_allowed,
            'no_of_reserve_allowed' => $this->no_of_reserve_allowed,
            'no_of_day_reserve_allowed' => $this->no_of_day_reserve_allowed,
        ];
    }
}
