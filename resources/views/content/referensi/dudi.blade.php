@extends('layouts/contentLayoutMaster')

@section('title', 'Data DUDI')

@section('content')
@livewire('referensi.data-dudi')
@endsection
@section('page-script')
<script>
    var detilModal = document.getElementById('detilModal'),
        anggotaAktPd = document.getElementById('anggotaAktPd');
    detilModal.addEventListener('hidden.bs.modal', function (event) {
        console.log('detilModal');
        Livewire.emit('cancel')
    })
    anggotaAktPd.addEventListener('hidden.bs.modal', function (event) {
        console.log('anggotaAktPd');
        Livewire.emit('cancel')
    })
    Livewire.on('close-modal', event => {
        $('#detilModal').modal('hide');
        $('#anggotaAktPd').modal('hide');
    })
</script>
@endsection
