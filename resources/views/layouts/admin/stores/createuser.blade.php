@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Add User</h2>

    <form action="{{ route('stores.storeuser') }}" method="POST">
    @csrf
    <div>
        <label>Name:</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div>
        <label>Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div>
        <label>Password:</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div>
        <label>Role:</label>
        <select name="role"  class="form-select" required>
            <option value="staff">Staff</option>
            <option value="manager">Manager</option>
        </select>
    </div>

    <div class="form-group">
        <label>Assign Tenants:</label>
        <div>
            @foreach($tenants as $tenant)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tenants[]" id="tenant_{{ $tenant->id }}" value="{{ $tenant->id }}" style="width: 1.5em; height: 1.5em;">
                   &nbsp; <label class="form-check-label" for="tenant_{{ $tenant->id }}" style="font-size: 1.2em;">{{ $tenant->id }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <button class="btn btn-success" type="submit">Create User</button>
</form>

</div>
@endsection
