<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\IssueController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\GithubAuthService;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect('/auth/github'); // Redirect users to GitHub login
})->name('login');

// GitHub OAuth Routes
Route::get('/auth/github', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/github/callback', function (GithubAuthService $githubAuthService) {
    $githubAuthService->handleGithubCallback();
    return redirect('/issues');
});

// GitHub Issues Routes
Route::middleware('auth')->group(function () {
    Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
    Route::get('/issues/show', [IssueController::class, 'show'])->name('issues.show');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
