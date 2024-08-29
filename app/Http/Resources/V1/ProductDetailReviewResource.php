<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailReviewResource extends JsonResource
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
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'rating' => $this->rating,
            'content' => $this->content,
            'seller_reply' => $this->seller_reply
        ];  


    }
}
