<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PagingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->currentPage(),
            'per_page' => $this->perPage(),
            'last_page' => $this->lastPage(),
            'url_path' => $this->path(),
            // 'first_page_url' => $this->url(1),
            //'next_page_url' => $this->nextPageUrl(),
            //'prev_page_url' => $this->previousPageUrl(),
            'record_from' => $this->firstItem(),
            'record_to' => $this->lastItem(),
            'record_count' => $this->count(),
            'total_record' => $this->total(),
        ];
    }
}
