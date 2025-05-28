<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClaimResource extends JsonResource
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
            'item' => new ItemResource($this->whenLoaded('item')),
            'item_id' => $this->item_id,
            'claimer_id' => $this->claimer_id,
            'claimer_name' => $this->claimer_name,
            'claimer_email' => $this->claimer_email,
            'claimer_phone' => $this->claimer_phone,
            'ownership_proof' => $this->ownership_proof,
            'notes' => $this->notes,
            'claim_date' => $this->claim_date,
            'status' => $this->status,
            'proof_document_url' => $this->proof_document ? url('storage/' . $this->proof_document) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
