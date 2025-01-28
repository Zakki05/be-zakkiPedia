<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PemesananDetailResource extends JsonResource
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
            'pemesanan' =>$this->pemesanan,
            'produk_detail' =>$this->produkDetail,
            'harga_produk' =>$this->harga_produk,
            'jumlah' =>$this->jumlah,
            'total_harga' =>$this->total_harga,
            'gambar' => $this->produkDetail->produk->gambar,
            'nama_produk' => $this->produkDetail->produk->nama_produk,
        ];
    }
}
