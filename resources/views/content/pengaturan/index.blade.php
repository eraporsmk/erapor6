@extends('layouts/contentLayoutMaster')

@section('title', 'Pengaturan Umum')

@section('content')
@livewire('pengaturan.index')
@endsection
@section('vendor-style-salah')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('vendor-script-salah')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

