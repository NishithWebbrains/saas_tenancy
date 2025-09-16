<form action="{{ route('stores.pos-vendor-update', $store) }}" method="POST" id="swiftpos-form">
    @csrf @method('PUT')
<div class="card mb-3 h-100">
    <div class="card-body">
        <h5>SwiftPOS Credential</h5>
        <div class="row">
            <div class=" mb-2">
                <label>Vendor Identifier</label>
                <input type="text" name="swiftpos_vendor_identifier" class="form-control" value="{{ old('swiftpos_vendor_identifier', $store->swiftpos_vendor_identifier) }}" placeholder="Enter Vendor Identifier">
            </div>
            
        </div>
        <div class="row d-flex justify-content-center">
                <div class="col-auto mx-2">
                    <button class="btn btn-success">Save</button> 
                </div>
                <div class="col-auto mx-2">
                    <button class="btn btn-success">Authorize</button>
                </div>
        </div>
    </div>
</div>
</form>
