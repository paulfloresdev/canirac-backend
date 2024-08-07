<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'label',
        'url',
    ];

    // Opcional: si quieres definir qué campos deben ser visibles
    protected $visible = [
        'id',
        'type',
        'label',
        'url',
        'created_at',
        'updated_at',
    ];
}
