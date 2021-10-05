<?php

namespace App\Http\Controllers;

use App\Criteria\TimelineCriteriaCriteria;
use App\Models\Post;
use App\Models\UserFollower;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostRepository $postRepository;

    public function __construct(PostRepository $repository)
    {
        $this->postRepository = $repository;
    }

    public function index()
    {
        $following = auth()->user()->follows()->pluck('user_id');

        $this->postRepository->pushCriteria(new TimelineCriteriaCriteria($following));

        $posts = $this->postRepository->paginate(6);

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'body' => 'required'
        ]);

        $this->postRepository->create([
            'user_id' => auth()->user()->id,
            'body' => request('body'),
            'published_at' => Carbon::now(),
        ]);

        return back(302, [], '/posts');
    }

    public function show($id)
    {
        $following = auth()->user()->follows()->pluck('user_id')->all();
        $following[] += auth()->user()->id;

        $result = $this->postRepository->findWhere([
            ['user_id', 'IN', $following],
            'id' => $id
        ])->first();

        if(is_null($result)) {
            throw new ModelNotFoundException();
        }

        return view('posts.show', [
            'post' => $result
        ]);
    }
}
