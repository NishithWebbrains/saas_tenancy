@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Store Users</h2>
    <!-- <a href="{{ route('admin.store-users.create') }}" class="btn btn-primary mb-3">+ Add Store User</a> -->

    <table class="table table-bordered" id="store-users-table">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Stores</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#store-users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('stores.storeusersdata') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'stores', name: 'users' },
            
        ]
    });
});
</script>
@endpush
