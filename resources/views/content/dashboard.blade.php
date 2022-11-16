@extends('layouts/contentLayoutMaster')

@section('title', 'Beranda')

@section('content')
    @role('admin', session('semester_id'))
        @livewire('dashboard.admin')
    @endrole
    @role('guru', session('semester_id'))
        @livewire('dashboard.guru')
    @endrole
    @role('siswa', session('semester_id'))
        @livewire('dashboard.siswa') 
    @endrole
    @role('user', session('semester_id'))
        @livewire('dashboard.user')
    @endrole
@endsection