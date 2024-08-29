<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductTableResource extends JsonResource
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
            'slug' => $this->slug,
            'category' => $this->category->name,
            'stock' => $this->stock,
            'review_count' => count($this->reviews),
            'price' => $this->price, 
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d')
        ];
    }
}
