@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Stores</h2>
    <a href="{{ route('stores.create') }}" class="btn btn-primary mb-3">+ Add Store</a>
    <a href="{{ route('stores.createuser') }}" class="btn btn-primary mb-3">+ Add User</a>
    <table class="table table-bordered" id="stores-table">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#stores-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('stores.data') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'slug', name: 'slug' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
