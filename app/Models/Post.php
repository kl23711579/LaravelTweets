<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'body',
        'stars',
        'published_at'
    ];

    protected $hidden = ['updated_at', 'created_at'];

    protected $with = ['author'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reply()
    {
        return $this->hasMany(Reply::class, 'post_id')->latest('published_at');
    }

    public function star()
    {
        return $this->hasOne(Star::class, 'post_id');
    }

    public function scopeTrending($query)
    {
        return $query->orderBy('stars', 'desc')->take(10);
    }
}
