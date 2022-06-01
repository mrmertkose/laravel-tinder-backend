<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUserDetail(): HasOne
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'id');
    }

    public function getUserEvents(): HasMany
    {
        return $this->hasMany(UserEvent::class, 'user_id', 'id');
    }

    public function getUserPhotos(): HasMany
    {
        return $this->hasMany(UserPhoto::class, 'user_id', 'id');
    }

    public function getFindUser($id)
    {
        $user['info'] = User::query()
            ->select('id', 'name', 'birthday', 'gender', 'searching_gender')
            ->leftJoin('users_events', 'users.id', '=', 'users_events.user_id')
            ->leftJoin('users_details', 'users.id', '=', 'users_details.user_id')
            ->whereNotIn('users.id', function ($query) use ($id) {
                $query->from('users_events')
                    ->select('users_events.user_liked_id')
                    ->where('users_events.user_id', '=', $id);
            })->whereIn('users_details.gender', function ($query) use ($id) {
                $query->from('users_details')
                    ->select('users_details.searching_gender')
                    ->where('users_details.user_id', '=', $id);
            })->where('users.id', '!=', $id)
            ->first();

        $user['photos'] = !is_null($user['info']) ? UserPhoto::query()->select('image_name as image', 'sort')->where('user_id', $user['info']->id)->orderBy('sort')->get() : [];

        return $user;
    }
}
