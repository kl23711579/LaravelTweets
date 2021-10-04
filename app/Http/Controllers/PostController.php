<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserFollower;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $following_ids_db = UserFollower::where('follower_id', auth()->user()->id)->get('user_id');
        $following_ids = $following_ids_db->map(function($following_id) {
            return $following_id->user_id;
        })->toArray();
        array_push($following_ids, auth()->user()->id);
        return view('posts.index', [
            'posts' => Post::whereIn('user_id', $following_ids)
                ->latest('published_at')
                ->paginate(6)
                ->withQueryString(),
        ]);
    }

    public function store(Post $post)
    {
        request()->validate([
            'body' => 'required'
        ]);

        $post = new Post();
        $post->user_id = auth()->user()->id;
        $post->body = request('body');
        $post->published_at = Carbon::now();
        $post->save();

        return back();
    }
}
