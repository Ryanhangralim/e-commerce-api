<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->first_name . " " . $this->last_name,
            'slug' => create_slug($this->first_name . " " . $this->last_name),
            'email' => $this->email,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d')
            // Add more fields as necessary
        ];
    }
}
