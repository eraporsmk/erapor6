@extends('layouts/fullLayoutMaster')

@section('title', 'Register')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
  <div class="auth-wrapper auth-basic px-2">
    <div class="auth-inner my-2">
      <!-- Register Basic -->
      <div class="card mb-0">
        <div class="card-body">
          <a href="#" class="brand-logo m-0">
          <img src="{{asset('images/logo.png')}}" alt="logo" srcset="" style="height:28px">
            <h2 class="brand-text text-primary ms-1">{{config('app.name')}}</h2>
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
          <form class="auth-register-form mt-2" method="POST" action="{{ route('register') }}">
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
              <label for="register-email" class="form-label">Email Dapodik</label>
              <input type="text" class="form-control @error('email') is-invalid @enderror" id="register-email"
                name="email" placeholder="Email Dapodik" aria-describedby="register-email" tabindex="2"
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
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
              <div class="mb-1">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="terms" name="terms" tabindex="4" />
                  <label class="form-check-label" for="terms">
                    I agree to the <a href="{{ route('terms.show') }}" target="_blank">terms_of_service</a> and
                    <a href="{{ route('policy.show') }}" target="_blank">privacy_policy</a>
                  </label>
                </div>
              </div>
            @endif
            <button type="submit" class="btn btn-primary w-100" tabindex="5">Register</button>
          </form>

          <p class="text-center mt-2">
            <span>Sudah Terdaftar?</span>
            @if (Route::has('login'))
              <a href="{{ route('login') }}">
                <span>Login Disini!</span>
              </a>
            @endif
          </p>
          <hr>
          <p class="text-center"><strong class="text-primary">&copy; Direktorat Sekolah Menengah Kejuruan {{date('Y')}}</strong></p>
        </div>
      </div>
      <!-- /Register basic -->
    </div>
  </div>
@endsection
