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

    public static function isMatchCounter($newEventObj): int
    {
        return self::query()
            ->whereIn('user_id', [$newEventObj->user_id,$newEventObj->user_liked_id])
            ->whereIn('user_liked_id', [$newEventObj->user_id,$newEventObj->user_liked_id])
            ->where('status', '=', 1)
            ->count('users_events_id');
    }
}
