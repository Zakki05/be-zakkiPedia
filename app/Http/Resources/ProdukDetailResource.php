<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'produk' =>$this->produk,
            'ukuran' =>$this->ukuran,
            'stok' =>$this->stok,
            'harga' =>$this->harga,
        ];
    }
}
