@extends('layouts/fullLayoutMaster')

@section('title', 'Login')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
  <div class="auth-wrapper auth-basic px-2">
    <div class="auth-inner my-2">
      <div class="card">
        <div class="card-body">
          <div class="mb-2">
            <x-jet-authentication-card-logo />
          </div>

          <div>
            {!! $policy !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
