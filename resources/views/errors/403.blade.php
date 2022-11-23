{{--
@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
--}}
@extends('layouts/fullLayoutMaster')

@section('title', 'Akses Terbatas')

@section('content')
<div class="row">
  <div class="col-12 p-4">
    <h4 class="mb-2">Akses Terbatas</h4>
    <div class="alert alert-primary" role="alert">
      <div class="alert-body">
        <strong>Info:</strong> Pengguna tidak memiliki hak akses ke laman ini.
      </div>
    </div>
  </div>
</div>
@endsection
