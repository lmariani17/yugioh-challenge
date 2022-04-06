<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 *     title="Image",
 *     description="Card's image",
 * )
 */
class Image extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'extension', 'file'];

    /**
     * @OA\Property(property="id", description="Image's ID", format="")
     */
    private int $id;

    /**
     * @OA\Property(property="name", description="Fila name", format="")
     */
    private string $name;

    /**
     * @OA\Property(property="extension", description="File's extension", format="")
     */
    private string $extension;

    /**
     * @OA\Property(property="file", description="Base 64 code", format="")
     */
    private string $file;
}
