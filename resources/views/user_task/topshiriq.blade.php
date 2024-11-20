@extends('layouts.main')

@section('content')
    <h1>My Tasks</h1>

    @if($tasks && $tasks->isNotEmpty())
        <ul>
            @foreach($tasks as $task)
                <li>{{ $task->name }} - {{ $task->status }}</li>
            @endforeach
        </ul>
    @else
        <p>No tasks available.</p>
    @endif
@endsection
