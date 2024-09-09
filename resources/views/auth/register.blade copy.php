@extends('layouts.simple')

@section('content')
<div id="page-container">

<!-- Main Container -->
<main id="main-container">

  <!-- Page Content -->
  <div class="hero-static d-flex align-items-center">
    <div class="w-100">
      <!-- Sign Up Section -->
      <div class="bg-body-light">
        <div class="content content-full">
          <div class="row g-0 justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4 py-4 px-4 px-lg-5">
              <!-- Header -->
              <div class="text-center">
                <p class="mb-2">
                  <i class="fa fa-2x fa-circle-notch text-primary"></i>
                </p>
                <h1 class="h4  mb-1">
                  Buat Akun Baru
                </h1>
                <p class="text-muted mb-3">
                  Get your access today in one easy step
                  </h2>
              </div>
              <!-- END Header -->

              <!-- Sign Up Form -->
              <!-- jQuery Validation (.js-validation-signup class is initialized in js/pages/op_auth_signup.min.js which was auto compiled from _js/pages/op_auth_signup.js) -->
              <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                <form method="POST" action="{{ route('register') }}">
                        @csrf
                    <div class="py-3">
                        <div class="mb-4">
                            <input type="text" class="form-control form-control-lg form-control-alt @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Username">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <input type="email" class="form-control form-control-lg form-control-alt @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" id="email" name="email" placeholder="Email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <input type="password" class="form-control form-control-lg form-control-alt @error('password') is-invalid @enderror" name="password" id="password" required autocomplete="new-password" placeholder="Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="mb-4">
                            <input type="password" class="form-control form-control-lg form-control-alt" id="password-confirm" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                        </div>

                        <div class="mb-4">
                            <div class="d-md-flex align-items-md-center justify-content-md-between">
                                    <a class="fs-sm fw-medium" href="{{route('login')}}">Login</a>
                            </div>
                        </div>
                    </div>
                <div class="row justify-content-center">
                  <div class="col-lg-6 col-xxl-5">
                    <button type="submit" class="btn w-100 btn-alt-success">
                      <i class="fa fa-fw fa-plus me-1 opacity-50"></i> Sign Up
                    </button>
                  </div>
                </div>
              </form>
              <!-- END Sign Up Form -->
            </div>
          </div>
        </div>
      </div>
      <!-- END Sign Up Section -->

      <!-- Footer -->
      <div class="fs-sm text-center text-muted py-3">
        <strong>Member</strong> &copy; <span data-toggle="year-copy"></span>
      </div>
      <!-- END Footer -->
    </div>


  </div>
  <!-- END Page Content -->
</main>
<!-- END Main Container -->
</div>
<!-- END Page Container -->
@endsection
