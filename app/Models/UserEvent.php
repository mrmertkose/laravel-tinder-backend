<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    use HasFactory;

    protected $table = 'users_events';

    protected $fillable = [
        'user_id',
        'user_liked_id',
        'status'
    ];
}
