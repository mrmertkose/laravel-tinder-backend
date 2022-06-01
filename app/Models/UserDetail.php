<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $table = 'users_details';

    protected $fillable = [
        'user_id',
        'user_detail',
        'gender',
        'searching_gender',
    ];
}
