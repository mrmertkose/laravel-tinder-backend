<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhoto extends Model
{
    use HasFactory;

    protected $table = 'users_photos';

    protected $fillable = [
        'user_id',
        'image_name',
        'sort'
    ];
}
