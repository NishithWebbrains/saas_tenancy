@extends('layouts.tenant.shopfrontpos.shopfrontpos')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="container">
    <h2>Store Users</h2>
            @php
                $tenantId = request()->route('tenant')
                ?? request()->segment(1)
                ?? request()->input('tenant')
                ?? null;
            @endphp
    <a href="{{ route('shopfrontpos.createuser', ['tenant' => $tenantId]) }}" class="btn btn-primary mb-3">+ Add User</a>

    <table id="usersTable" class="table table-bordered">
      <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
      </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('shopfrontpos.storeusersdata', ['tenant' => $tenantId]) }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
