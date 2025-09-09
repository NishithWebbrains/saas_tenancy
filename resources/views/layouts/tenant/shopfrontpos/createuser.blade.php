@extends('layouts.tenant.shopfrontpos.shopfrontpos')

@section('content')
<div class="container">
    <h2>Add User</h2>
            @php
                $tenantId = request()->route('tenant')
                ?? request()->segment(1)
                ?? request()->input('tenant')
                ?? null;
            @endphp
<form action="{{ route('shopfrontpos.storeuser', ['tenant' => $tenantId]) }}" method="POST">
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
    <br>
    

    <button class="btn btn-success" type="submit">Create User</button>
</form>

</div>
@endsection
