<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 *     title="Card",
 *     description="Card information",
 * )
 */
class Card extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'first_edition', 'serial_code', 'type', 'attack', 'defense', 'star', 'amount', 'subtype_id', 'image_id'];

    /**
     * @OA\Property(property="id", description="Card's ID", format="")
     */
    private int $id;

    /**
     * @OA\Property(property="name", description="Card's name", format="")
     */
    private string $name;

    /**
     * @OA\Property(property="description", description="Card's description", format="")
     */
    private string $description;

    /**
     * @OA\Property(property="first_edition", description="Is first edition", format="")
     */
    private bool $first_edition;

    /**
     * @OA\Property(property="serial_code", description="Card's serial code", format="")
     */
    private string $serial_code;

    /**
     * @OA\Property(property="type", description="Card's type", format="", enum={"Monster", "Magic", "Trap"})
     */
    private string $type;

    /**
     * @OA\Property(property="attack", description="Attack of card", format="")
     */
    private string $attack;

    /**
     * @OA\Property(property="defense", description="Defense of card", format="")
     */
    private string $defense;

    /**
     * @OA\Property(property="star", description="Quality of card", format="")
     */
    private string $star;

    /**
     * @OA\Property(property="amount", description="Amount of card", format="")
     */
    private float $amount;

    /**
     * @OA\Property(property="subtype_id", description="Card's subtype", format="")
     */
    private int $subtype_id;

    /**
     * @OA\Property(property="image_id", description="Card's image", format="")
     */
    private int $image_id;

    /**
     * Get the image associated with the card.
     */
    public function subtype()
    {
        return $this->belongsTo(Subtype::class);
    }

    /**
     * Get the image associated with the card.
     */
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
