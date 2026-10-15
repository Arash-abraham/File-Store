<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $images = collect($this->image_urls ?? [])
            ->filter()
            ->map(fn ($path) => $this->publicUrl((string) $path))
            ->values();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'original_price' => (int) $this->original_price,
            'price' => $this->final_price,
            'discount_percent' => $this->original_price > 0 && $this->final_price < $this->original_price
                ? (int) round((1 - ($this->final_price / $this->original_price)) * 100)
                : 0,
            'images' => $images,
            'cover_url' => $images->first(),
            'key_features' => $this->key_features ?? [],
            'rating' => round((float) ($this->approved_reviews_avg_rating ?? 0), 1),
            'reviews_count' => (int) ($this->approved_reviews_count ?? 0),
            'availability' => (bool) $this->availability,
            'status' => $this->status,
            'files_count' => $this->whenLoaded('files', fn () => $this->files->count()),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }

    private function publicUrl(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}
