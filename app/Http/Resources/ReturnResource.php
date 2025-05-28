<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReturnResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'item' => new ItemResource($this->whenLoaded('item')),
            'item_id' => $this->item_id,
            'returner_id' => $this->returner_id,
            'returner_name' => $this->returner_name,
            'returner_nim' => $this->returner_nim,
            'returner_email' => $this->returner_email,
            'returner_phone' => $this->returner_phone,
            'where_found' => $this->where_found,
            'item_photo_url' => $this->item_photo ? url('storage/' . $this->item_photo) : null,
            'return_date' => $this->return_date,
            'notes' => $this->notes,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
