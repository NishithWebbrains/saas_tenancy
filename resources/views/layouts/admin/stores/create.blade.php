@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Add Store</h2>

    <form action="{{ route('stores.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Store Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        
        <div class="mb-3">
            <label class="form-label">POS Type</label>
            <select name="pos_type" class="form-select" required>
                <option value="">-- Select POS Type --</option>
                <option value="shopfrontpos">ShopfrontPOS</option>
                <option value="swiftpos">SwiftPOS</option>
                <option value="abspos">ABSPOS</option>
                {{-- Add more POS types here as needed --}}
            </select>
        </div>

        <button class="btn btn-success">Save</button>
    </form>
</div>
@endsection
