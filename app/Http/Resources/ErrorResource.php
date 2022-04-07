<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode()
        ];
    }
}
