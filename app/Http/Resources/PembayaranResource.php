<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PembayaranResource extends JsonResource
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
            'pelanggan' =>$this->pelanggan,
            'kode_pembayaran' =>$this->kode_pembayaran,
            'tanggal_pembayaran' =>$this->tanggal_pembayaran,
            'metode_pembayaran' =>$this->metode_pembayaran,
            'pembayaran' =>$this->pembayaran,
            'status' =>$this->status,
        ];
    }
}
