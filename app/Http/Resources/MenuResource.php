<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'menu_nama' => $this->menu_nama,
            'menu_foto' => $this->menu_foto,
            'menu_harga' => $this->menu_harga,
            'menu_ketersediaan' => $this->menu_ketersediaan,
            'menu_deskripsi' => $this->menu_deskripsi,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
