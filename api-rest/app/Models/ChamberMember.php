<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamberMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'img_path'
    ];
}
