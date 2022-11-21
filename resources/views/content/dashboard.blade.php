@extends('layouts/contentLayoutMaster')

@section('title', 'Beranda')

@section('content')
    @role('admin', session('semester_id'))
        @livewire('dashboard.admin')
    @elserole('guru', session('semester_id'))
        @livewire('dashboard.guru')
    @elserole('siswa', session('semester_id'))
        @livewire('dashboard.siswa') 
    @elserole('user', session('semester_id'))
        @livewire('dashboard.user')
    @else
    @livewire('dashboard.tamu')
    @endrole
@endsection