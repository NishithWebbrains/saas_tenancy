@extends('swiftpos::layouts.swiftpos')

@section('title', 'Permissions')
@section('page-title', 'Permissions')

@section('content')
<div class="container">
    <h2>Permissions for Role: {{ $role->name }}</h2>
    @php
        $tenantId = request()->route('tenant')
            ?? request()->segment(1)
            ?? request()->input('tenant')
            ?? null;
        $roleId = $role->id ?? null;
    @endphp

    <a href="{{ route('swiftpos.roles', ['tenant' => $tenantId]) }}" class="btn btn-danger mb-3">← Back to Roles</a>

    <form id="permissionsForm" method="POST" action="{{ route('swiftpos.addrole', ['tenant' => $tenantId, 'role' => $roleId]) }}">
        @csrf
        <table id="permissionsTable" class="table table-bordered">
          <thead>
            <tr>
                <th>Module</th>
                @foreach($permissions as $permission)
                    <th class="text-center">{{ ucfirst($permission->name) }}</th>
                @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($menus as $menu)
                <tr>
                    <td>{{ $menu->name }}</td>
                    @foreach($permissions as $permission)
                        <td class="text-center">
                            <input type="checkbox" 
                                   name="permissions[{{ $menu->id }}][]" 
                                   value="{{ $permission->id }}"
                                   {{ in_array($permission->id, $assignedPermissions[$menu->id] ?? []) ? 'checked' : '' }}>
                        </td>
                    @endforeach
                </tr>
            @endforeach
          </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Save Permissions</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log("Permissions page loaded for tenant: {{ $tenantId }}, role: {{ $roleId }}");
});
</script>
@endpush
