<form action="{{ route('stores.zkong-update', $store) }}" method="POST">
    @csrf 
    @method('PUT')

<div class="card mb-3 h-100">
    <div class="card-body">
        <h5>Zkong Credential</h5>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label>API Base URL : https://esl.zkong.com</label>
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-6 mb-2">
                <label>Client ID</label>
                <input type="text" name="clientid" class="form-control"  value="{{ old('clientid', $store->clientid) }}" placeholder="Enter Client ID">
            </div>
            <div class="col-md-6 mb-2">
                <label>Client Password</label>
                <input type="password" name="client_password" class="form-control" value="{{ old('client_password', $store->client_password) }}" placeholder="Enter Client Password">
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-6 mb-2">
                <label>Store ID</label>
                <input type="text" name="store_id" class="form-control" value="{{ old('store_id', $store->store_id) }}" placeholder="Enter Store ID">
            </div>
            <div class="col-md-6 mb-2">
                <label>External Store ID</label>
                <input type="text" name="external_storeid" class="form-control" value="{{ old('external_storeid', $store->external_storeid) }}" placeholder="Enter External Store ID">
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