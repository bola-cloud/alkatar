<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // Basic response data
        $data = [
            'id' => $this->id,
            'en_Product_Name' => $this->en_Product_Name,
            'fr_Product_Name' => $this->fr_Product_Name,
            'Price' => $this->Price,
            'Discount' => $this->Discount,
            'en_Description' => strip_tags($this->en_Description),
            'fr_Description' => strip_tags($this->fr_Description),
            'Primary_Image' => $this->resource->resizeImage(),
        ];
        if ($request->query('scope') !== 'mini') {
            $data = array_merge($data, [
                'Category_Id' => $this->Category_Id,
                'en_Product_Slug' => $this->en_Product_Slug,
                'fr_Product_Slug' => $this->fr_Product_Slug,
                'en_About' => $this->en_About,
                'fr_About' => $this->fr_About,
                'ItemTag' => $this->ItemTag,
                'Discount_Price' => $this->Discount_Price,
                'Quantity' => $this->Quantity,
                'Sold' => $this->Sold,
                'Image2' => $this->Image2,
                'Image3' => $this->Image3,
                'Image4' => $this->Image4,
                'Image5' => $this->Image5,
                'Featured_Product' => $this->Featured_Product,
                'Best_Selling' => $this->Best_Selling,
                'New_Arrival' => $this->New_Arrival,
                'On_Sale' => $this->On_Sale,
                'Status' => $this->Status,
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
            ]);
        }

        return $data;
    }
}
