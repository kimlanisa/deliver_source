@extends('layouts.simple')

@section('content')
<div class="hero-static d-flex align-items-center">
    <div class="content">
        <div class="row justify-content-center push">      
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="block block-rounded mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title"></h3>
                        <div class="block-options">
                        <a class="btn-block-option fs-sm" href="op_auth_reminder.html">Forgot Password?</a>
                        <a class="btn-block-option" href="{{route('register')}}" data-bs-toggle="tooltip" data-bs-placement="left" title="New Account">
                            <i class="fa fa-user-plus"></i>
                        </a>
                        </div>
                    </div>
                    <div class="block-content">
                        <!-- <div class="card-header">{{ __('Login') }}</div> -->
                        
                        <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-3">
                            <div class="text-center">
                                <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('media/avatars/avatar0.jpg') }}" alt="">
                                <p class="fw-medium text-muted">
                                Welcome, please login.
                                </p>
                            </div>
                            
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="py-3">
                                    <div class="mb-4">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror form-control-alt form-control-lg" name="email" value="{{ old('email') }}" placeholder="Username"  required autocomplete="email">
                                        <!-- <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus> -->
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                               
                                    <div class="mb-4">
                                        <!-- <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"> -->
                                        <input type="password" class="form-control @error('password') is-invalid @enderror form-control-alt form-control-lg" id="password" required name="password" placeholder="Password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="container my-3">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn w-50 btn-alt-primary">
                                            <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i>{{ __('Login') }}
                                        </button>
                                        <!-- @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="fs-sm text-muted text-center">
            <strong>MAPPING PASIEN</strong> &copy; <span data-toggle="year-copy"></span>
        </div>
    </div>
</div>
@endsection
