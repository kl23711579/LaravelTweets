<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SessionController extends Controller
{
    protected SessionRepository $repository;

    public function __construct(SessionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function twitterRedirect()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function twitterCallback()
    {
        $user = Socialite::driver('twitter')->user();

        $this->_registerOrLoginTwitterUser($user);

        return redirect('/posts');
    }

    protected function _registerOrLoginTwitterUser($incomingUser)
    {
        $user = $this->repository->updateOrCreate(['twitter_id' => $incomingUser->id],[
            'name' => $incomingUser->name,
            'email' => $incomingUser->email,
            'nickname' => $incomingUser->nickname,
            'twitter_id' => $incomingUser->id,
            'password' => bcrypt('password'),
        ]);

        auth()->login($user);
    }

    public function destroy()
    {
        auth()->logout();

        return redirect('/');
    }
}
