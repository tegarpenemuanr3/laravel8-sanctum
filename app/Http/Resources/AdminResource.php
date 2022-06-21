<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            'admin_nama' => $this->admin_nama,
            'admin_phone' => $this->admin_phone,
            'admin_email' => $this->admin_email,
            'admin_foto' => $this->admin_foto,
            'password' => $this->password,
        ];
    }
}
