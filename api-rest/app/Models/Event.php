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
        'ver_img_path',
        'hor_img_path',
    ];

    // Si deseas utilizar casts para los campos
    protected $casts = [
        'price' => 'float',
        'date' => 'date',
    ];
}
