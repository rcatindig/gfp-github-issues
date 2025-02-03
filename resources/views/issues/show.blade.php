@extends('layouts.app')

@section('title', 'Issue #'.$issue['number'])

@section('content')
    <h2 class="mb-4">Issue #{{ $issue['number'] }}</h2>
    <a href="{{ route('issues.index') }}" class="btn btn-primary mb-3">Back to Issues</a>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $issue['title'] }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Created: {{ \Carbon\Carbon::parse($issue['created_at'])->diffForHumans() }}</h6>
            <p class="card-text">{!! nl2br(e($issue['body'])) !!}</p>
        </div>
    </div>
@endsection
