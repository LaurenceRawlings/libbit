<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        $socialAccount = Socialite::driver('github')->user();
        $user = User::where('github_id', $socialAccount->getId())->first();

        if (! $user) {
            $user = User::create([
                'name' => $socialAccount->getName(),
                'email' => $socialAccount->getEmail(),
                'github_id' => $socialAccount->getId(),
                'github_token' => $socialAccount->token,
                'github_refresh_token' => $socialAccount->refreshToken,
            ]);

            $user->ownedTeams()->save(Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'personal_team' => true,
            ]));
        } else {
            $user->update([
                'github_token' => $socialAccount->token,
                'github_refresh_token' => $socialAccount->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}
