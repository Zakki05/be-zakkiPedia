<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OngkirResource extends JsonResource
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
            'kode_ongkir' =>$this->kode_ongkir,
            'kota' =>$this->kota,
            'biaya' =>$this->biaya,
        ];
    }
}
