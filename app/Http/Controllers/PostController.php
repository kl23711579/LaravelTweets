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
        $following = auth()->user()->follows()->pluck('user_id')->toArray();
        array_push($following, auth()->user()->id);

        $posts = $this->repository->scopeQuery(function($query) use ($following) {
            return $query->whereIn('user_id', $following)->latest('published_at');
        })->paginate(6);

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    public function store(Post $post)
    {
        request()->validate([
            'body' => 'required'
        ]);

        $this->repository->create([
            'user_id' => auth()->user()->id,
            'body' => request('body'),
            'published_at' => Carbon::now(),
        ]);

        return back();
    }
}
