<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="{{ url('/admin') }}" class="brand-link">
      <img src="{{ url('vendor/adminlte/assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
      <span class="brand-text fw-light">Elabels</span>
    </a>
  </div>
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <!-- copy nav markup from index.html or build your menu -->
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" id="navigation">
        <li class="nav-item menu-open">
          <a href="{{ route('admin.dashboard') }}" class="nav-link active">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard <i class="nav-arrow bi bi-chevron-right"></i></p>
          </a>
        </li>
        <li class="nav-item menu-open">
          <a href="{{ route('stores.index') }}" class="nav-link active">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Stores</p>
          </a>
        </li>
        <li class="nav-item menu-open">
          <a href="{{ route('stores.viewusers') }}" class="nav-link active">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Store Users</p>
          </a>
        </li>
        <!-- more items -->
      </ul>
    </nav>
  </div>
</aside>
