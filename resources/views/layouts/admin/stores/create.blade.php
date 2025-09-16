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
            <select name="pos_type" id="pos_type" class="form-select" required>
                <option value="shopfrontpos">ShopfrontPOS</option>
                <option value="swiftpos">SwiftPOS</option>
                <option value="abspos">ABSPOS</option>
            </select>
        </div>

       
        <button class="btn btn-success">Save</button>
    </form>
</div>

<script>
// document.addEventListener('DOMContentLoaded', function() {
//     const posTypes = ['shopfrontpos', 'swiftpos', 'abspos'];
//     const select = document.getElementById('pos_type');

//     function showCredentials() {
//         const selected = select.value;
//         posTypes.forEach(type => {
//             const el = document.getElementById(type + '-credentials');
//             if (el) {
//                 el.style.display = (type === selected) ? 'block' : 'none';
//             }
//         });
//     }

//     select.addEventListener('change', showCredentials);
//     showCredentials(); // Initial trigger on page load
// });
</script>

@endsection
