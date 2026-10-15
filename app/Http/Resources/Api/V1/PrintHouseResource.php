<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrintHouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'city' => $this->city,
            'province' => $this->province,
            'description' => $this->description,
            'capabilities' => $this->capabilities ?? [],
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'unit_price' => (int) $this->unit_price,
            'hardcover_surcharge' => (int) $this->hardcover_surcharge,
            'delivery_fee' => (int) $this->delivery_fee,
            'delivery_days' => (int) $this->delivery_days,
            'min_quantity' => (int) $this->min_quantity,
            'max_quantity' => (int) $this->max_quantity,
            'rating' => round((float) $this->rating, 1),
            'orders_count' => $this->when(isset($this->print_orders_count), fn () => (int) $this->print_orders_count),
        ];
    }
}
