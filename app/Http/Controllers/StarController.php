<?php

namespace App\Http\Controllers;

use App\Models\Star;
use App\Repositories\PostRepository;
use App\Repositories\StarRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class StarController extends Controller
{
    protected UserRepository $userRepository;
    protected PostRepository $postRepository;
    protected StarRepository $starRepository;

    public function __construct(
        UserRepository $userRepository,
        PostRepository $postRepository,
        StarRepository $starRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->starRepository = $starRepository;
    }

    public function index()
    {
        return csrf_token();
    }

    /**
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($id)
    {
        $following = $this->userRepository
            ->getFollowingUsers(auth()->user()->id)
            ->pluck('user_id')
            ->all();
        $following[] += auth()->user()->id;

        $post = $this->postRepository->with(['star'])->findWhere([
            ['user_id', 'IN', $following],
            'id' => $id
        ])->first();

        // cannot star nonexist post
        if(is_null($post)) {
            throw new ModelNotFoundException();
        }

        $response = $this->starRepository->update([
            'number' => $post->star->number+1
        ], $post->star->id);

        return response()->json($response);
    }
}
