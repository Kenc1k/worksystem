<!-- resources/views/topshiriq/index.blade.php -->

@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Task List</h1>
        <a href="{{ route('topshiriq.create') }}" class="btn btn-primary">Create New Task</a>

        <!-- Filter Form -->
        <form action="{{ route('topshiriq.index') }}" method="GET" class="mt-3">
            <div class="row">
                <div class="col-md-3">
                    <label for="from_date">From Date:</label>
                    <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="to_date">To Date:</label>
                    <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-success btn-block">Filter</button>
                </div>
            </div>
        </form>

        <!-- Task Table -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col">ID</th>
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
