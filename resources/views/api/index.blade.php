@extends('layouts/contentLayoutMaster')

@php
$breadcrumbs = [['link' => 'home', 'name' => 'Home'], ['name' => 'API Tokens']];
@endphp

@section('title', 'API Tokens')


@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
  @livewire('api.api-token-manager')
@endsection
