<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <!-- <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li> -->
    </ul>

    <ul class="navbar-nav ms-auto">
      <!-- search/messages/notifications/user menu (copy from index.html) -->
      <!-- Example user image using asset -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <img src="{{ url('vendor/adminlte/assets/img/user2-160x160.jpg') }}" class="user-image rounded-circle shadow" alt="User Image"/>
          <span class="d-none d-md-inline">storeuser</span>
        </a>
        <!-- dropdown content ... -->
      </li>
    </ul>
    <div class="d-flex">
            @php
                $tenantId = request()->route('tenant')
                ?? request()->segment(1)
                ?? request()->input('tenant')
                ?? null;
            @endphp
                <form method="POST" action="{{ route('swiftpos.logout', ['tenant' => $tenantId]) }}">
                @csrf
                  <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
          
        </div>
  </div>
</nav>
