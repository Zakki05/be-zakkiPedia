<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PelangganResource extends JsonResource
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
            'user' =>$this->user,
            'nama_lengkap' =>$this->nama_lengkap,
            'nama_panggilan' =>$this->nama_panggilan,
            'alamat' =>$this->alamat,
            'telepon' =>$this->telepon,
        ];
    }
}
