@extends('shopfrontpos::layouts.shopfrontpos')

@section('title', 'Roles')
@section('page-title', 'Roles')

@section('content')
<div class="container">
    <h2>Roles</h2>
    @php
        $tenantId = request()->route('tenant')
        ?? request()->segment(1)
        ?? request()->input('tenant')
        ?? null;
    @endphp

    <a href="{{ route('shopfrontpos.createrole', ['tenant' => $tenantId]) }}" class="btn btn-primary mb-3">+ Add Role</a>

    <table id="rolesTable" class="table table-bordered">
      <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
      </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#rolesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('shopfrontpos.roledata', ['tenant' => $tenantId]) }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
