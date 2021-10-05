<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'body',
        'stars',
        'published_at'
    ];

    protected $hidden = ['updated_at', 'created_at'];

    protected $with = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author()
    {
        $this->belongsTo(User::class, 'user_id');
    }

    public function post()
    {
        $this->belongsTo(Post::class);
    }
}
