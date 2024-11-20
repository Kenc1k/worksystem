<!-- resources/views/topshiriq/index.blade.php -->

@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Task List</h1>
        <a href="{{ route('topshiriq.create') }}" class="btn btn-primary">Create New Task</a>
        
        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Category</th>
                    <th scope="col">Ijrochi</th>
                    <th scope="col">Muddat</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->category->name ?? 'N/A' }}</td>
                        <td>{{ $task->ijrochi }}</td>
                        <td>{{ $task->muddat }}</td>
                        <td>
                            <a href="{{ route('topshiriq.edit', $task->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('topshiriq.destroy', $task->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
