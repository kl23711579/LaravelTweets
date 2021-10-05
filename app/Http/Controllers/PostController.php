<?php

namespace App\Http\Controllers;

use App\Criteria\TimelineCriteriaCriteria;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostController extends Controller
{
    protected PostRepository $postRepository;
    protected UserRepository $userRepository;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $following = $this->getFollowing()->follows()->pluck('user_id')->all();

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
        $following = $this->getFollowing()->follows()->pluck('user_id')->all();
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
    protected function getFollowing()
    {
        return $this->userRepository->find(auth()->user()->id);
    }
}
