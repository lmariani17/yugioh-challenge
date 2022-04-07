<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 *     title="Subtype",
 *     description="Card's subtype",
 *     example={
 *          "id": 1,
 *          "name": "Normal Monster"
 *     }
 * )
 */
class SubtypeResource extends JsonResource
{
    /**
     * @OA\Property(type="integer", property="id", description="Subtype's ID", format=""),
     * @OA\Property(type="string", property="name", description="Subtype's name", format="")
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
