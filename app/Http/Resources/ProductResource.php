<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'Category_Id' => $this->Category_Id,
            'en_Product_Name' => $this->en_Product_Name,
            'fr_Product_Name' => $this->fr_Product_Name,
            'en_Product_Slug' => $this->en_Product_Slug,
            'fr_Product_Slug' => $this->fr_Product_Slug,
            'en_About' => $this->en_About,
            'fr_About' => $this->fr_About,
            'ItemTag' => $this->ItemTag,
            'Price' => $this->Price,
            'Discount' => $this->Discount,
            'Discount_Price' => $this->Discount_Price,
            'Quantity' => $this->Quantity,
            'Sold' => $this->Sold,
            'Primary_Image' => asset(ProductImage() . $this->Primary_Image),
            'Image2' => $this->Image2,
            'Image3' => $this->Image3,
            'Image4' => $this->Image4,
            'Image5' => $this->Image5,
            'Featured_Product' => $this->Featured_Product,
            'Best_Selling' => $this->Best_Selling,
            'New_Arrival' => $this->New_Arrival,
            'On_Sale' => $this->On_Sale,
            'Status' => $this->Status,
            'en_Description' => $this->en_Description,
            'fr_Description' => $this->fr_Description,
            'en_ShippingReturn' => $this->en_ShippingReturn,
            'fr_ShippingReturn' => $this->fr_ShippingReturn,
            'en_AdditionalInformation' => $this->en_AdditionalInformation,
            'fr_AdditionalInformation' => $this->fr_AdditionalInformation,
            'Voucher' => $this->Voucher,
            'digital_type' => $this->digital_type,
            'digital_file' => $this->digital_file,
            'digital_link' => $this->digital_link,
            'license_name' => $this->license_name,
            'license_key' => $this->license_key,
            'affiliate_link' => $this->affiliate_link,
            'type' => $this->type,
            'points' => $this->points,
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'colors' => ColorResource::collection($this->whenLoaded('colors')),
            'sizes' => SizeResource::collection($this->whenLoaded('sizes')),
            'product_tags' => ProductTagResource::collection($this->whenLoaded('product_tags')),
        ];
    }
}
