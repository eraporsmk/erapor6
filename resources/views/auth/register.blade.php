@php
$configData = Helper::applClasses();
@endphp
@extends('layouts/fullLayoutMaster')

@section('title', 'Registrasi Pengguna')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
<div class="auth-wrapper auth-cover">
  <div class="auth-inner row m-0">
    <!-- Brand logo-->
    <a class="brand-logo" href="{{url('/')}}">
      <!--img src="{{asset('images/tutwuri.png')}}" alt="logo" srcset="" style="height:50px"-->
      <img class="img-fluid" src="{{asset('images/logo-name.svg')}}" alt="{{config('app.name')}}" style="height:50px" />
    </a>
    <!-- /Brand logo-->

    <!-- Left Text-->
    <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
      <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
        @if($configData['theme'] === 'dark')
          <img class="img-fluid" src="{{asset('images/login-v2-dark.svg')}}" alt="Login V2" />
          @else
          <img class="img-fluid" src="{{asset('images/login3.png')}}" alt="Login V2" />
          @endif
      </div>
    </div>
    <!-- /Left Text-->

    <!-- Login-->
    <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5 border-start">
      <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
            <h1 class="brand-text judul ms-1 text-center">
              <img src="{{asset('images/logo.png')}}" alt="logo" srcset="" style="height:28px">
              {{config('app.name')}}
            </h1>
        <!--h2 class="card-title fw-bold mb-0 text-primary text-center">{{config('app.name')}}</h2-->
        <h3 class="card-text judul mb-2 text-center">Versi {{config('global.app_version')}}</h3>
        @if (session('status'))
            <div class="alert alert-danger mb-1 rounded-0" role="alert">
              <div class="alert-body">
                {{ session('status') }}
              </div>
            </div>
          @endif
          @if (session('success'))
            <div class="alert alert-success mb-1 rounded-0" role="alert">
              <div class="alert-body">
                {{ session('success') }}
              </div>
            </div>
          @endif
        <form class="auth-login-form mt-2" method="POST" action="{{ route('register') }}">
          @csrf
          <div class="mb-1">
            <label for="register-username" class="form-label">NPSN</label>
            <input type="text" class="form-control @error('npsn') is-invalid @enderror" id="register-username"
              name="npsn" placeholder="NPSN" aria-describedby="register-username" tabindex="1" autofocus
              value="{{ old('npsn') }}" />
            @error('npsn')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div class="mb-1">
            <label class="form-label" for="login-email">Email Dapodik</label>
            <input type="text" class="form-control @error('email') is-invalid @enderror" id="login-email" name="email"
                placeholder="Email Dapodik" aria-describedby="login-email" tabindex="1" autofocus
                value="{{ old('email') }}" />
            @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
          </div>
          <div class="mb-1">
            <label for="register-password" class="form-label">Password Dapodik</label>
            <div class="input-group input-group-merge form-password-toggle @error('password') is-invalid @enderror">
              <input type="password" class="form-control form-control-merge @error('password') is-invalid @enderror"
                id="register-password" name="password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="register-password" tabindex="3" />
              <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
            @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div class="mb-1">
            <label for="register-password-confirm" class="form-label">Konfirmasi Password</label>

            <div class="input-group input-group-merge form-password-toggle">
              <input type="password" class="form-control form-control-merge" id="register-password-confirm"
                name="password_confirmation"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="register-password" tabindex="3" />
              <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100" tabindex="4">Register</button>
        </form>
        <p class="text-center mt-2">
          <span>Sudah Terdaftar?</span>
          @if (Route::has('login'))
            <a href="{{ route('login') }}">
              <span>Login Disini!</span>
            </a>
          @endif
        </p>
      </div>
    </div>
    <!-- /Login-->
  </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{asset(mix('js/scripts/pages/auth-login.js'))}}"></script>
@endsection
