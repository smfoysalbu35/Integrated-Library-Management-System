<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatronAttendanceLogResource extends JsonResource
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
            'date_in' => $this->date_in,
            'time_in' => $this->time_in,
            'date_out' => $this->date_out,
            'time_out' => $this->time_out,
            'status' => $this->status,

            'patron' => [
                'id' => $this->patron->id,
                'patron_no' => $this->patron->patron_no,
                'name' => "{$this->patron->first_name} {$this->patron->last_name}",
                'image' => $this->patron->image,
            ],
        ];
    }
}
