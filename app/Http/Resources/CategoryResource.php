<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'id' => $this->id,
            'en_Category_Name' => $this->en_Category_Name,
            'fr_Category_Name' => $this->fr_Category_Name,
            'en_Category_Slug' => $this->en_Category_Slug,
            'fr_Category_Slug' => $this->fr_Category_Slug,
            'en_Description' => $this->en_Description,
            'fr_Description' => $this->fr_Description,
            'Status' => $this->Status,
            'order' => $this->order,
            'Category_Icon' => asset(CategoryImage() . $this->Category_Icon),
//            'sub_categories' => SubcategoryResource::collection($this->subCategories),
        ];
    }
}
