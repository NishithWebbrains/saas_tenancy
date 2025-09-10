<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Elabels - Electronic Shelf Label Manufacturer | ESL Australia</title>
    <link rel="icon" type="image/x-icon" href="{{ url('admin/dist/img/fav-logo.png') }}"/>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('admin/dist/css/adminlte.min.css') }}">

    <style>
        .login_new_card {
            background-color: transparent;
            padding: 20px 0px;
        }
        .login_new_card .card {
            border: 1px solid #999999;
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box login_new_card">
    <div class="card">
        <div class="card-body login-card-body">
            <div class="login-logo">
                <img src="{{ url('admin/dist/img/-new-logo.png') }}" alt="Elabels Logo"
                      width="250px" style="opacity: .8">
            </div>
            <p class="login-box-msg">Welcome to Elabels Integration</p>
            <h4 class="login-box-msg">Sign In</h4>

            {{-- Show session status (like logout success) --}}
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @php
                $errors = $errors ?? session('errors', new \Illuminate\Support\ViewErrorBag);
            @endphp
            {{-- Show login errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $tenantId = request()->route('tenant')
                ?? request()->segment(1)
                ?? request()->input('tenant')
                ?? null;
            @endphp

            <form method="POST" action="{{ route('shopfrontpos.login.submit', ['tenant' => $tenantId]) }}">
                @csrf

                {{-- Email --}}
                <label for="email">Email</label>
                <div class="input-group mb-3">
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autofocus
                           autocomplete="username" style="border-right:1px solid #ced4da;">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Password --}}
                <label for="password">Password</label>
                <div class="input-group mb-3">
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password" required autocomplete="current-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Remember Me</label>
                        </div>
                    </div>
                </div>

                {{-- Forgot password link --}}
                <p class="mb-2 text-right">
                    <a href="{{ route('password.request') }}"> Forgot Password ?</a>
                </p>

                {{-- Submit button --}}
                <div class="row justify-content-center">
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block align-items-center"
                                style="background-color:#021324c4;">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ url('admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('admin/dist/js/adminlte.min.js') }}"></script>

<script>
    // Password toggle eye
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
