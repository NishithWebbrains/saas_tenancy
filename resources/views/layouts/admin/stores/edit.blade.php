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
<br/>
        <div class="row">
        <div class="col-md-6" id="zkong-credentials" >
                @include('layouts.admin.stores.partials.zkong-card')
            </div>

            <div class="col-md-6" id="shopfrontpos-credentials" style="display:none;">
                @include('layouts.admin.stores.partials.shopfrontpos-card')
            </div>
            <div class="col-md-6" id="swiftpos-credentials" style="display:none;">
                @include('layouts.admin.stores.partials.swiftpos-card')
            </div>
            <div class="col-md-6" id="abspos-credentials" style="display:none;">
                @include('layouts.admin.stores.partials.abspos-card')
            </div>
        </div>
        <br>

       
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const posTypes = ['shopfrontpos', 'swiftpos', 'abspos'];
    
    // Use existing store's pos_type from Blade
    const existingPosType = @json($store->pos_type);

    // Show only the credentials for the existing pos_type
    posTypes.forEach(type => {
        const el = document.getElementById(type + '-credentials');
        if (el) {
            el.style.display = (type === existingPosType) ? 'block' : 'none';
        }
    });
});
</script>

@endsection
