@extends('layouts/contentLayoutMaster')

@section('title', 'Rasio Nilai Akhir')

@section('content')
@livewire('perencanaan.rasio-nilai-akhir')
@endsection
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset(mix('vendors/css/extensions/toastr.min.css'))}}">
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset(mix('css/base/plugins/extensions/ext-component-toastr.css'))}}">
@endsection
@section('vendor-script')
<script src="{{asset(mix('vendors/js/extensions/toastr.min.js'))}}"></script>
@endsection
