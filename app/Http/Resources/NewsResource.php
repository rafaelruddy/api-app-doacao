<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'banners' => $this->getMedia('banners')->map(function ($media) {
                return $media->getUrl(); // Usa o método getUrl para obter a URL completa da imagem
            }),
            'created_at' => $this->created_at
        ];
    }
}

