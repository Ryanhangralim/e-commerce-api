<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessMainPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'product_count' => count($this->products),
            'reviews' => number_format($this->reviews()->avg('rating'), 2, '.', '.'), 
            'review_count' => count($this->reviews)
        ];
    }
}
