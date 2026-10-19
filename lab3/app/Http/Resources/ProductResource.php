<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'category' => $this->category->name,
            'category_id' => $this->category_id,
            'operation' => $this->operation,
            'price' => (string) $this->price,
            'currency' => $this->currency,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'image_url' => asset($this->image),
            'contact_name' => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'status' => $this->status,
            'published_at' => $this->published_at->format('Y-m-d'),
            'attributes' => (object) $this->resource->characteristics,
        ];
    }
}
