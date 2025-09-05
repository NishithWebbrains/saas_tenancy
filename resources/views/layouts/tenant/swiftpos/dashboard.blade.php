@extends('layouts.tenant.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
  <div class="row">
    <div class="col-lg-12">
    <h4>Welcome to Store <b>{{ $tenantDetails->pluck('name')->first() }}</b></h4>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-primary">
        <div class="inner">
          <h3>{{ $products->count() }}</h3>
          <p>Total Products</p>
          <p><strong>Products:</strong> {{ $products->pluck('name')->join(', ') }}</p>
        </div>
        <a href="#" class="small-box-footer link-light">More info <i class="bi bi-link-45deg"></i></a>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Optional: JS for dashboard
</script>
@endpush
