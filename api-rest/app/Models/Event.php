<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'date',
        'time',
        'address',
        'lat',
        'long',
        'ver_img_path',
        'hor_img_path',
    ];

    // Si deseas utilizar casts para los campos
    protected $casts = [
        'price' => 'float',
        'date' => 'date',
    ];

    /**
     * Obtener la URL completa de la imagen vertical.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getVerImgPathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    /**
     * Obtener la URL completa de la imagen horizontal.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getHorImgPathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
