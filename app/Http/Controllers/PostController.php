<?php

namespace App\Http\Controllers;

use App\Criteria\TimelineCriteriaCriteria;
use App\Repositories\PostRepository;
use App\Repositories\StarRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostController extends Controller
{
    protected PostRepository $postRepository;
    protected UserRepository $userRepository;
    protected StarRepository $starRepository;

    public function __construct(
        PostRepository $postRepository,
        UserRepository $userRepository,
        StarRepository $starRepository
    )
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->starRepository = $starRepository;
    }

    public function index()
    {
        $following = $this->userRepository
            ->getFollowingUsers(auth()->user()->id)
            ->pluck('user_id')
            ->all();

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

        $reponse = $this->postRepository->create([
            'user_id' => auth()->user()->id,
            'body' => request('body'),
            'published_at' => Carbon::now(),
        ]);

        // each post need create star record
        $this->starRepository->create([
            'post_id' => $reponse->id
        ]);

        return back(302, [], '/posts');
    }

    public function show($id)
    {
        $following = $this->userRepository
            ->getFollowingUsers(auth()->user()->id)
            ->pluck('user_id')
            ->all();
        $following[] += auth()->user()->id;

        $result = $this->postRepository->with(['reply', 'author'])->findWhere([
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
