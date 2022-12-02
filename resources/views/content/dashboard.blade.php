@extends('layouts/contentLayoutMaster')

@section('title', 'Beranda')

@section('content')
    @if($user->hasRole('admin', session('semester_id')))
        @livewire('dashboard.admin')
    @elseif($user->hasRole('tu', session('semester_id')))
        @livewire('dashboard.admin')
    @elseif($user->hasRole('guru', session('semester_id')))
        @livewire('dashboard.guru')
    @elseif($user->hasRole('siswa', session('semester_id')))
        @livewire('dashboard.siswa')
    @elseif($user->hasRole('user', session('semester_id')))
        @livewire('dashboard.user')
    @else
        @livewire('dashboard.tamu')
    @endif
    {{--
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
    --}}
@endsection