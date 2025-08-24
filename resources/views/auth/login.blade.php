@extends('layouts.auth')

@section('content')
<body class="bg-default">
  <!-- Navbar -->
  <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
    <div class="container">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">
        <div class="navbar-collapse-header">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/img/brand/blue.png') }}">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
           
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('login') }}" class="nav-link">
              <span class="nav-link-inner--text">Login</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('register') }}" class="nav-link">
              <span class="nav-link-inner--text">Register</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main content -->
  <div class="main-content">
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <h1 class="text-white">Welcome!</h1>
              <p class="text-lead text-white">Silakan login dengan email & password Anda</p>
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>

    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary border-0 mb-0">

            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-4">
                <small>Login dengan email & password</small>
              </div>

              <!-- pesan error -->
              @if(session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                </div>
              @endif

              <!-- pesan success -->
              @if(session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div>
              @endif

              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                  @if(session('show_forgot_password'))
                    <hr class="my-3">
                    <div class="text-center">
                      <a href="{{ route('forgot.password') }}" class="btn btn-outline-warning btn-sm">
                        <i class="ni ni-key-25"></i> Lupa Password?
                      </a>
                    </div>
                  @endif
                </div>
              @endif

              <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input name="email" class="form-control" placeholder="Email" type="email" required value="{{ old('email') }}">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input name="password" id="passwordField" class="form-control" placeholder="Password" type="password" required>
                    <div class="input-group-append">
                      <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="ni ni-eye-17" id="eyeIcon"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id="customCheckLogin" type="checkbox" name="remember" value="1">
                  <label class="custom-control-label" for="customCheckLogin">
                    <span class="text-muted">Remember me</span>
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" id="loginButton" class="btn btn-primary my-4" disabled style="opacity: 0.5;">Sign in</button>
                </div>
              </form>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="{{ route('forgot.password') }}" class="text-light"><small>Lupa password?</small></a>
            </div>
            <div class="col-6 text-right">
              <a href="{{ route('register') }}" class="text-light"><small>Buat akun baru</small></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const rememberCheckbox = document.getElementById('customCheckLogin');
      const loginButton = document.getElementById('loginButton');
      const togglePassword = document.getElementById('togglePassword');
      const passwordField = document.getElementById('passwordField');
      const eyeIcon = document.getElementById('eyeIcon');

      // Function to update button state
      function updateButtonState() {
        if (rememberCheckbox.checked) {
          loginButton.disabled = false;
          loginButton.style.opacity = '1';
        } else {
          loginButton.disabled = true;
          loginButton.style.opacity = '0.5';
        }
      }

      // Listen for changes on the checkbox
      rememberCheckbox.addEventListener('change', updateButtonState);

      // Initial state check
      updateButtonState();

      // Toggle password visibility
      togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        // Toggle eye icon
        if (type === 'text') {
          eyeIcon.className = 'ni ni-satisfied'; // Eye closed icon
        } else {
          eyeIcon.className = 'ni ni-eye-17'; // Eye open icon
        }
      });

      // Prevent form submission if remember is not checked
      document.querySelector('form').addEventListener('submit', function(e) {
        if (!rememberCheckbox.checked) {
          e.preventDefault();
          alert('Anda harus mencentang "Remember me" untuk dapat login.');
          return false;
        }
      });
    });
  </script>
</body>
@endsection