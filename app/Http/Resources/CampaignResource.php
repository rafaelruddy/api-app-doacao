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
            'date' => $this->date,
            'donated_items_quantity' => $this->current_donation_quantity(),
            'items_quantity_objective' => $this->items_quantity_objective,
            'addressess' => AddressResource::collection($this->whenLoaded('addressess')),
            'necessary_items' => NecessaryItemResource::collection($this->whenLoaded('necessary_items'))
        ];
    }
}
