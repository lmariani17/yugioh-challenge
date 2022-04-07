<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 *     title="User",
 *     description="User",
 *     example={
 *          "id": 1,
 *          "name": "Ali Cole",
 *          "email": "tillman.edwardo@example.org"
 *      }
 * )
 */
class UserResource extends JsonResource
{
    /**
     * @OA\Property(type="integer", property="id", description="User's ID", format=""),
     * @OA\Property(type="string", property="name", description="User name", format=""),
     * @OA\Property(type="email", property="email", description="User's email", format=""),
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
