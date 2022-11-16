@extends('layouts/fullLayoutMaster')

@section('title', 'Login')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection
@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('content')
<?php
$semester = \App\Models\Semester::whereHas('tahun_ajaran', function($query){
  $query->where('periode_aktif', 1);
})->orderBy('semester_id', 'DESC')->get();
?>
  <div class="auth-wrapper auth-basic px-2">
    <div class="auth-inner my-2">
      <!-- Login basic -->
      <div class="card mb-0">
        <div class="card-body">
          <a href="#" class="brand-logo m-0">
          <img src="{{asset('images/logo.png')}}" alt="logo" srcset="" style="height:28px">
            <h2 class="brand-text text-primary ms-1">{{config('app.name')}} </h2>
          </a>
          <h4 class="card-title mb-1 text-center">Versi {{config('global.app_version')}} <small>Beta</small></h4>
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
              <label for="login-email" class="form-label">Email</label>
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
              <div class="input-group input-group-merge form-password-toggle">
                <input type="password" class="form-control form-control-merge" id="login-password" name="password"
                  tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="login-password" />
                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
              </div>
            </div>
            <div class="mb-1" wire:ignore>
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
                <label class="form-check-label" for="remember"> Simpan login </label>
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
          <hr>
          <p class="text-center"><strong class="text-primary">&copy; Direktorat Sekolah Menengah Kejuruan {{date('Y')}}</strong></p>
          {{--

          <div class="auth-footer-btn d-flex justify-content-center">
            <a href="#" class="btn btn-facebook">
              <i data-feather="facebook"></i>
            </a>
            <a href="#" class="btn btn-twitter white">
              <i data-feather="twitter"></i>
            </a>
            <a href="#" class="btn btn-google">
              <i data-feather="mail"></i>
            </a>
            <a href="#" class="btn btn-github">
              <i data-feather="github"></i>
            </a>
          </div>
          --}}
        </div>
      </div>
      <!-- /Login basic -->
    </div>
  </div>
@endsection
@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
@endsection
