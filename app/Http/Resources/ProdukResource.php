<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
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
            'kategori' =>$this->kategori,
            'kode_produk' =>$this->kode_produk,
            'nama_produk' =>$this->nama_produk,
            'gambar' =>$this->gambar,
        ];
    }
}
