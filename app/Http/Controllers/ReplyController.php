<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use App\Repositories\ReplyRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
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

        $reply = [
            'user_id' => auth()->user()->id,
            'post_id' => $postId,
            'body' => request('body'),
            'published_at' => Carbon::now(),
        ];

        //check is author
        if($this->postRepository->find($postId)->user_id === auth()->user()->id){
            $this->replyRepository->create($reply);
        }

        // check is following user
        // get following user
    }
}
