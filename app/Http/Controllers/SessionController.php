<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SessionController extends Controller
{
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

        return redirect('/');
    }

    protected function _registerOrLoginTwitterUser($incomingUser)
    {
        $user = User::where('twitter_id', $incomingUser->id)->first();
        if (! $user) {
           $user = new User();
           $user->name = $incomingUser->name;
           $user->email = $incomingUser->email;
           $user->nickname = $incomingUser->nickname;
           $user->twitter_id = $incomingUser->id;
           $user->password = bcrypt('password');
           $user->save();
        }

        auth()->login($user);
    }

    public function destroy()
    {
        auth()->logout();

        return redirect('login');
    }
}
