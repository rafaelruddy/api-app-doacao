<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
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
            'campaign' => new CampaignResource($this->whenLoaded('campaign')),
            'items' => DonatedItemResource::collection($this->whenLoaded('items')),
            'status' => $this->status,
        ];
    }
}
