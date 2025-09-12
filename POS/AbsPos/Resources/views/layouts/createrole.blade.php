@extends('abspos::layouts.abspos')

@section('content')
<div class="container">
    <h2>Add Role</h2>
    @php
        $tenantId = request()->route('tenant')
        ?? request()->segment(1)
        ?? request()->input('tenant')
        ?? null;
    @endphp

    <form action="{{ route('abspos.addrole', ['tenant' => $tenantId]) }}" method="POST">
        @csrf
        <div>
            <label>Role Name:</label>
            <input type="text" name="name" class="form-control" placeholder="Enter role name" required>
        </div>


        <br>
        <button class="btn btn-success" type="submit">Create Role</button>
    </form>
</div>
@endsection
