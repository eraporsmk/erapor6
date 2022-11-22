@php
$configData = Helper::applClasses();
@endphp
@extends('layouts/fullLayoutMaster')

@section('title', 'Login Pengguna')

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
        <form class="auth-login-form mt-2" method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-1">
            <label class="form-label" for="login-email">Email</label>
            <input type="text" class="form-control @error('email') is-invalid @enderror" id="login-email" name="email"
                placeholder="admin@erapor-smk.net" aria-describedby="login-email" tabindex="1" autofocus
                value="{{ old('email') }}" />
            @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
          </div>
          <div class="mb-1">
            <div class="d-flex justify-content-between">
              <label class="form-label" for="login-password">Password</label>
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                  <small>Lupa Password?</small>
                </a>
              @endif
            </div>
            <div class="input-group input-group-merge form-password-toggle @error('password') is-invalid @enderror">
              <input class="form-control form-control-merge" id="login-password" type="password" placeholder="············" aria-describedby="login-password" tabindex="2" name="password" />
              <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
          </div>
          <div class="mb-1">
            <label for="semester" class="form-label">Tahun Pelajaran</label>
            <select name="semester" id="semester" class="form-control form-control-merge select2">
              @foreach($semester as $s)
              <option value="{{$s->semester_id}}" {{($s->periode_aktif) ? 'selected' : ''}}>{{$s->nama}}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-1">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" name="remember" tabindex="3"
                  {{ old('remember') ? 'checked' : '' }} />
              <label class="form-check-label" for="remember-me"> Simpan login</label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100" tabindex="4">Login</button>
        </form>
        @if (Route::has('register'))
          <p class="text-center mt-2">
            <span>Pengguna Baru?</span>
              <a href="{{ route('register') }}">
                <span>Register Disini</span>
              </a>
          </p>
          @endif
          {{--
          <hr>
          <p class="text-center"><strong class="text-primary">&copy; Direktorat Sekolah Menengah Kejuruan {{date('Y')}}</strong></p>
          --}}
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
