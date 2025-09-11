@extends('swiftpos::layouts.swiftpos')

@section('title', 'Permissions')
@section('page-title', 'Permissions')

@section('content')
<div class="container">
    <h2>Permissions</h2>
    @php
        $tenantId = request()->route('tenant')
            ?? request()->segment(1)
            ?? request()->input('tenant')
            ?? null;
        $roleId = request()->route('role') ?? null;
    @endphp

    <a href="{{ route('swiftpos.roles', ['tenant' => $tenantId]) }}" class="btn btn-danger mb-3">← Back to Roles</a>

    <form id="permissionsForm" method="POST" action="{{ route('swiftpos.addrole', ['tenant' => $tenantId, 'role' => $roleId]) }}">
        @csrf
        <table id="permissionsTable" class="table table-bordered">
          <thead>
            <tr>
                <th>Module</th>
                <th class="text-center">Authorize</th>
                <th class="text-center">View</th>
            </tr>
          </thead>
          <tbody>
            {{-- Example static rows, later you can loop modules from DB --}}
            <tr>
                <td>dashboard</td>
                <td class="text-center"><input type="checkbox" name="permissions[products][create]"></td>
                <td class="text-center"><input type="checkbox" name="permissions[products][update]"></td>
            </tr>
            
          </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Save Permissions</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // If you later fetch permissions dynamically, you can load them with AJAX here
    console.log("Permissions page loaded for tenant: {{ $tenantId }}, role: {{ $roleId }}");
});
</script>
@endpush
