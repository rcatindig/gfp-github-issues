<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    public function index()
    {
        $user = Auth::user();


        $response = Http::withToken($user->github_token)
            ->get('https://api.github.com/search/issues', [
                'q' => "assignee:{$user->github_nickname} is:open",
                'sort' => 'created',
                'order' => 'desc'
            ]);

        if ($response->successful()) {
            $issues = $response->json()['items'];
        } else {
            return response()->json(['error' => 'Failed to fetch issue'], 500);;
        }

        return view('issues.index', compact('issues'));
    }

    public function show(Request $request)
    {
        $github_url = urldecode($request->query('url'));

        if (!$github_url) {
            return response()->json(['error' => 'GitHub URL is required'], 400);
        }

        $user = Auth::user();
        $response = Http::withToken($user->github_token)
            ->get($github_url);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch issue'], 500);
        }

        $issue = $response->json();

        return view('issues.show', compact('issue'));
    }

}
