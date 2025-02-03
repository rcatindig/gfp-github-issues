<?php

namespace App\Services;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GithubAuthService
{
    public function handleGithubCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'github_nickname' => $githubUser->nickname,
            'github_token' => $githubUser->token,   
        ]);

        Auth::login($user);
    }
}
