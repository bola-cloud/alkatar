<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SizeResource extends JsonResource
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
            'Size' => $this->Size,
            'Size_ar' => $this->Size_ar,
            'price' => $this->pivot->price,
            'weight' => $this->pivot->weight,
        ];
    }
}
