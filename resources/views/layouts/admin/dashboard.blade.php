@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
  <div class="row">
    <div class="col-lg-3 col-6">
      <div class="small-box text-bg-primary">
        <div class="inner"><h3>{{ $storeCount }}</h3><p>Stores</p></div>
        <a href="#" class="small-box-footer link-light">More info <i class="bi bi-link-45deg"></i></a>
      </div>
    </div>
    <!-- add other small boxes or copy more HTML from index.html -->
  </div>
@endsection

@push('scripts')
<script>
  // Optional: run demo charts like in index.html
</script>
@endpush
