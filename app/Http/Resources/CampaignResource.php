<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'description' => $this->description,
            'avatar' => $this->getFirstMediaUrl('avatar'),
            'banner' => $this->getFirstMediaUrl('banner'),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'donation_start_time' => $this->donation_start_time,
            'donation_end_time' => $this->donation_end_time,
            'donated_items_objective' => $this->donatedItemsObjective(),
            'donated_items_quantity' => $this->currentDonationQuantity(),
            'institution' => new InstitutionResource($this->whenLoaded('institution')),
            'addressess' => AddressResource::collection($this->whenLoaded('addressess')),
            'necessary_items' => NecessaryItemResource::collection($this->whenLoaded('necessary_items'))
        ];
    }
}
