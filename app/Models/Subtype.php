<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 *     title="Subtype",
 *     description="Card's subtype",
 * )
 */
class Subtype extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subtypes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @OA\Property(property="id", description="Subtype's ID", format="")
     */
    private int $id;

    /**
     * @OA\Property(property="name", description="Subtype's name", format="")
     */
    private string $name;
}
