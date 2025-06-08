<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ItemResource extends JsonResource
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
            'type' => $this->type,
            'item_name' => $this->item_name,
            'category' => $this->category,
            'date_of_event' => $this->date_of_event,
            'description' => $this->description,
            'location' => $this->location,
            'status' => $this->status,
            'photo_url' => $this->photo_path
                ? url('api/v1/images/' . $this->photo_path)
                : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'contact_info' => [
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'report_by' => $this->report_by,
            ],
        ];
    }
}
