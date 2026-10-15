<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'content' => empty($this->content) ? new \stdClass() : $this->content,
            'status' => $this->status,
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
