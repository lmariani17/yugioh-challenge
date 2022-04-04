<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
