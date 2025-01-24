<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'role' =>$this->role,
            'email' =>$this->email,
            'username' =>$this->username,
            'password' =>$this->password,
            'nama' =>$this->nama,
            'alamat' =>$this->alamat,
            'nama_lengkap' => $this->nama_lengkap,
            'nama_panggilan' => $this->nama_panggilan,
            'alamatPelanggan' => $this->alamatPelanggan,
            'telepon' => $this->telepon,
        ];
    }
}
