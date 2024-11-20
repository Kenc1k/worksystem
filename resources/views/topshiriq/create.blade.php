<!-- resources/views/topshiriq/create.blade.php -->

@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Create New Task</h1>

        <form action="{{ route('topshiriq.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="ijrochi">Ijrochi</label>
                <input type="text" name="ijrochi" id="ijrochi" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="muddat">Muddat</label>
                <input type="date" name="muddat" id="muddat" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="file">File</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="hudud_id">Hududs</label>
                <select name="hudud_id[]" id="hudud_id" class="form-control" multiple required>
                    @foreach($hududs as $hudud)
                        <option value="{{ $hudud->id }}">{{ $hudud->name }}</option>
                    @endforeach
                </select>
                
            </div>

            <button type="submit" class="btn btn-success mt-3">Create Task</button>
        </form>
    </div>
@endsection
