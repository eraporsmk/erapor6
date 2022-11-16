@extends('layouts/contentLayoutMaster')

@section('title', 'Akses Pengguna')

@section('content')
@livewire('pengaturan.users')
@endsection
@section('page-script')
<script>
    $('#generatePengguna').click(function(e){
        e.preventDefault()
        Livewire.emit('generatePengguna')
    })
    window.livewireLoading = false;
</script>
@endsection
@section('page-style')
<style>
    .overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: 1000;
        top: 40%;
        left: 0px;
        opacity: 0.7;
        filter: alpha(opacity=70);
    }
</style>
@endsection
