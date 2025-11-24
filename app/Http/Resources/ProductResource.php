<?php

namespace Vanguard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category' => $this->categories->pluck('id')->implode(','),
            'data' => $this->provider,
            'status' => $this->remote_id,
            'created' => $this->dataSource,
            'updated' => $this->name,
        ];
    }
}
