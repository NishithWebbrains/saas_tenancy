@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Store User</h2>

    <form action="{{ route('admin.store-users.update', $user) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label>New Password (leave empty if unchanged)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button class="btn btn-success">Update</button>
    </form>
</div>
@endsection
