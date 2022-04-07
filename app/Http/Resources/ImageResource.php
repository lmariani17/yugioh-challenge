<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 *     title="Image",
 *     description="Card's image",
 *     example={
 *          "id": 1,
 *          "name": "Dark Margician",
 *          "extension": "jpg",
 *          "file": "TWFuIGlzIGRpc3Rpbmd1aXNoZWQTWFuIGlzIGRpc3Rpbmd1aXNoZWQTWFuIGlzIGRpc3Rpbmd1aXNoZWQTWFuIGlzIGRpc3Rpbmd1aXNoZWQ",
 *     }
 * )
 */
class ImageResource extends JsonResource
{
    /**
     * @OA\Property(type="integer", property="id", description="Image's ID", format=""),
     * @OA\Property(type="string", property="name", description="Fila name", format=""),
     * @OA\Property(type="string", property="extension", description="File's extension", format=""),
     * @OA\Property(type="string", property="file", description="Base 64 code", format="")
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'extension' => $this->extension,
            'file' => $this->file,
        ];
    }
}
