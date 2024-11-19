@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 align="center">Edit Hudud</h1>
                <form action="{{ route('hudud.update', $hudud->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="" disabled>Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $hudud->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Hudud Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $hudud->name }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
