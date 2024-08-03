<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'en_BrandName' => $this->en_BrandName,
            'fr_BrandName' => $this->fr_BrandName,
            'en_BrandSlug' => $this->en_BrandSlug,
            'fr_BrandSlug' => $this->fr_BrandSlug,
            'BrandImage' => asset(BrandImage() . $this->BrandImage),
            'Status' => $this->Status,
        ];
    }
}
