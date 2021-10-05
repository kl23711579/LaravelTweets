<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            "twitter_id" => 'required',
            'password' => 'required'
        ]);

        if(! auth()->attempt($attributes)) {
            throw ValidationException::withMessages(['twitter_id' => 'Yout provided credentials could not be verfied.']);
        }

        session()->regenerate();

        return redirect('/posts');
    }

    public function twitterRedirect()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function twitterCallback()
    {
        $user = Socialite::driver('twitter')->user();

        $this->_registerOrLoginTwitterUser($user);

        session()->regenerate();

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
