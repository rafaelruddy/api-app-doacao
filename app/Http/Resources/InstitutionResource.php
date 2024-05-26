<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstitutionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'description' => $this->description,
            'phone' => $this->phone,
            'campaigns' => CampaignResource::collection($this->whenLoaded('campaigns')),
            'address' => new AddressResource($this->whenLoaded('address')),
        ];
    }
}
