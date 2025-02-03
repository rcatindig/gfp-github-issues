@extends('layouts.app')

@section('title', 'Your Open Issues')

@section('content')
    <h2 class="mb-4">Open Github Issues</h2>
    <div class="list-group">
        @foreach($issues as $issue)
            <a href="{{ route('issues.show', ['url' => urlencode($issue['url'])]) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">#{{ $issue['number'] }} - {{ $issue['title'] }}</h5>
                    <small>Created: {{ \Carbon\Carbon::parse($issue['created_at'])->diffForHumans() }}</small>
                </div>
            </a>
        @endforeach
    </div>
@endsection
