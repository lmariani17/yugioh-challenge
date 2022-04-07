<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 *     title="Card",
 *     description="Card information",
 *     example={
 *          "id": 1,
 *          "name": "Dark Magician",
 *          "description": "Desciption of Dark Magician",
 *          "first_edition": 1,
 *          "serial_code": 190124914710293,
 *          "type": "Monster",
 *          "attack": 2500,
 *          "defense": 2000,
 *          "star": 9,
 *          "amount": 1500.00,
 *          "subtype": {
 *              "id": 1,
 *              "name": "Normal Monster"
 *          },
 *          "image_id": {
 *              "id": 1,
 *              "name": "Dark Margician",
 *              "extension": "jpg",
 *              "file": "TWFuIGlzIGRpc3Rpbmd1aXNoZWQTWFuIGlzIGRpc3Rpbmd1aXNoZWQTWFuIGlzIGRpc3Rpbmd1aXNoZWQTWFuIGlzIGRpc3Rpbmd1aXNoZWQ",
 *          }
 *     }
 * )
 */
class CardResource extends JsonResource
{
    /**
     * @OA\Property(type="integer", property="id", description="Card's ID", format=""),
     * @OA\Property(type="string", property="name", description="Card's name", format=""),
     * @OA\Property(type="string", property="description", description="Card's description", format=""),
     * @OA\Property(type="boolean", property="first_edition", description="Is first edition", format=""),
     * @OA\Property(type="string", property="serial_code", description="Card's serial code", format=""),
     * @OA\Property(type="string", property="type", description="Card's type", format="", enum={"Monster", "Magic", "Trap"}),
     * @OA\Property(type="integer", property="attack", description="Attack of card", format=""),
     * @OA\Property(type="integer", property="defense", description="Defense of card", format=""),
     * @OA\Property(type="integer", property="star", description="Quality of card", format=""),
     * @OA\Property(type="number", property="amount", description="Amount of card", format=""),
     * @OA\Property(property="subtype", ref="#/components/schemas/SubtypeResource"),
     * @OA\Property(property="image", ref="#/components/schemas/ImageResource")
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
            'subtype' => new SubtypeResource($this->subtype),
            'image' => new ImageResource($this->image),
        ];
    }
}
