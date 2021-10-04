<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id');
    }

    public function follows()
    {
        return $this->hasMany(UserFollower::class, 'follower_id');
    }

    public function follower_posts()
    {
        return $this->hasManyThrough(Post::class, UserFollower::class, 'user_id', 'user_id', 'id', 'follower_id');
    }

    public function following_posts()
    {
        // users,id = user_followers.id
        // user_followers.id = posts.user_id
        return $this->hasManyThrough(Post::class, UserFollower::class, 'follower_id', 'user_id', 'id', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
