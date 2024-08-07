<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'partner_price',
        'price',
        'date',
        'time',
        'address',
        'lat',
        'long',
        'img_path',
    ];
}
