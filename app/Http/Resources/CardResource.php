<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'first_edition' => $this->first_edition,
            'type' => $this->type,
            'attack' => $this->attack,
            'defense' => $this->defense,
            'star' => $this->star,
            'amount' => $this->amount,
            'subtype' => $this->subtype,
            'image' => $this->image,
        ];
    }
}
