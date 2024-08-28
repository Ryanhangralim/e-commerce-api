<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerApplicationDetailResource extends JsonResource
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
            'user' => $this->whenLoaded('user'),
            'business_name' => $this->business_name,
            'business_description' => $this->business_description,
            'application_status' => $this->application_status,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d')
        ];
    }
}
