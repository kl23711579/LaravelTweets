<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use App\Repositories\ReplyRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    protected ReplyRepository $replyRepository;
    protected PostRepository $postRepository;
    protected UserRepository $userRepository;

    public function __construct(
        ReplyRepository $replyRepository,
        PostRepository $postRepository,
        UserRepository $userRepository
    )
    {
        $this->replyRepository = $replyRepository;
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function store($postId)
    {
        Request()->validate([
            'body' =>'required'
        ]);

        $following = $this->userRepository
            ->getFollowingUsers(auth()->user()->id)
            ->pluck('user_id')
            ->all();
        $following[] += auth()->user()->id;

        $result = $this->postRepository->findWhere([
            ['user_id', 'IN', $following],
            'id' => $postId
        ])->first();

        if(is_null($result)) {
            throw new ModelNotFoundException();
        }

        $this->replyRepository->create([
            'user_id' => auth()->user()->id,
            'post_id' => $postId,
            'body' => request('body'),
            'published_at' => Carbon::now(),
        ]);

        return redirect('/posts/'.$postId);


    }
}
