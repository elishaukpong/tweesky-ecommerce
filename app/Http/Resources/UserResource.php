<?php

namespace App\Http\Resources;

use App\Http\Resources\API\v1\School\SchoolResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'users',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'token' => $this->token,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ],
            'relationships' => [
                'products' => ProductResource::collection($this->whenLoaded('products')),
                'wishlists' => WishlistResource::collection($this->whenLoaded('wishlists')),
            ]
        ];
    }
}
