@extends('layouts/contentLayoutMaster')

@section('title', 'Data Ekstrakurikuler')

@section('content')
@livewire('referensi.data-ekstrakurikuler')
@endsection
@section('page-script')
<script>
    var anggotaEkskulModal = document.getElementById('anggotaEkskulModal')
    anggotaEkskulModal.addEventListener('hidden.bs.modal', function (event) {
        Livewire.emit('cancel')
    })
    Livewire.on('close-modal', event => {
        $('#anggotaEkskulModal').modal('hide');
    })
</script>
@endsection
