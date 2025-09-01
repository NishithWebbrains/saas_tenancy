@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Store</h2>

    <form action="{{ route('stores.update', $store) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Store Name</label>
            <input type="text" name="name" class="form-control" value="{{ $store->name }}" required>
        </div>

        

        <button class="btn btn-success">Update</button>
    </form>
</div>
@endsection
