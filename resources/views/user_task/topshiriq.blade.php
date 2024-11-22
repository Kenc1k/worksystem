@extends('layouts.main')

@section('content')
    <div class="container">
        
        <h1>My Tasks</h1>
        {{-- @php
            dd($tasks[0]->file);
        @endphp --}}
        @if($tasks && $tasks->isNotEmpty())
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th scope="col">Task Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Due Date</th>
                        <th scope="col">File</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->status ?? 'Pending' }}</td>
                            <td>{{ \Carbon\Carbon::parse($task->muddat)->format('d/m/Y') }}</td>
                            {{-- @php
                                dd($tasks);

                            @endphp --}}
                            <td>
                                @if($task->file)
                                    <a href="{{ asset('storage/' . $task->file) }}" target="_blank" class="btn btn-primary btn-sm">Download File</a>
                                @else
                                    No file uploaded
                                @endif
                            </td>

                            
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No tasks available.</p>
        @endif
    </div>
@endsection
