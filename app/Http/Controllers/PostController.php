<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserFollower;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('posts.index', [
            'posts' => auth()->user()->timeline(),
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
