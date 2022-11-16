@extends('layouts/contentLayoutMaster')

@section('title', 'Penilaian SMK PK')

@section('content')
@livewire('perencanaan.pk')
@endsection
@section('page-script')
<script>
    var addModal = document.getElementById('addModal'),
        viewModal = document.getElementById('viewModal'),
        deleteModal = document.getElementById('deleteModal'),
        copyModal = document.getElementById('copyModal');
    addModal.addEventListener('hidden.bs.modal', function (event) {
        Livewire.emit('cancel')
    })
    viewModal.addEventListener('hide.bs.modal', function (event) {
        console.log('viewModal');
        Livewire.emit('cancel')
    })
    deleteModal.addEventListener('hide.bs.modal', function (event) {
        console.log('deleteModal');
        Livewire.emit('cancel')
    })
    copyModal.addEventListener('hidden.bs.modal', function (event) {
        console.log('copyModal');
        Livewire.emit('cancel')
    })
    Livewire.on('close-modal', event => {
        $('#addModal').modal('hide');
        $('#viewModal').modal('hide');
        $('#deleteModal').modal('hide');
        $('#copyModal').modal('hide');
    })
</script>
@endsection