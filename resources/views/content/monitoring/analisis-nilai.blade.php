@extends('layouts/contentLayoutMaster')

@section('title', 'Analisis Nilai')

@section('content')
@livewire('monitoring.analisis-nilai')
@endsection
@section('vendor-script')
<script src="{{asset(mix('vendors/js/charts/apexcharts.min.js'))}}"></script>
@endsection
@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
@endsection
@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
<style>
    .tooltip-inner {text-align: left !important;}
</style>
@endsection
