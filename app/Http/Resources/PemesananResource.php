<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PemesananResource extends JsonResource
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
            'ongkir' =>$this->ongkir,
            'pembayaran' =>$this->pembayaran,
            'kode_pemesanan' =>$this->kode_pemesanan,
            'tanggal' =>$this->tanggal,
            'kecamatan' =>$this->kecamatan,
            'kelurahan' =>$this->kelurahan,
            'alamat' =>$this->alamat,
            'kode_pos' =>$this->kode_pos,
            'status' =>$this->status,
            'total_pemesanan' =>$this->total_pemesanan,
            'biaya' =>$this->biaya,
            'subtotal' =>$this->subtotal,
        ];
    }
}
