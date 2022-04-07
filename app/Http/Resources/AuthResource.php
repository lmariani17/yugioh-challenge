<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 *     title="Auth",
 *     description="Auth",
 *     example={
 *          "token": "1|Ke43aqsLWBAUoylqRJ9egUEueOrl6FfogFgwhCjP"
 *      }
 * )
 */
class AuthResource extends JsonResource
{
    /**
     * @OA\Property(type="string", property="access_token", description="User's access token", format=""),
     */
    public function toArray($request)
    {
        return [
            'access_token' => $this->plainTextToken,
        ];
    }
}
