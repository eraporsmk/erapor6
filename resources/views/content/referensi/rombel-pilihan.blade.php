@extends('layouts/contentLayoutMaster')

@section('title', 'Rombel Matpel Pilihan')

@section('content')
@livewire('referensi.rombel-pilihan')
@endsection
@section('page-script')
<script>
    var anggotaRombelModal = document.getElementById('anggotaRombelModal'),
        pembelajaranModal = document.getElementById('pembelajaranModal');
    if(pembelajaranModal){
        pembelajaranModal.addEventListener('hide.bs.modal', function (event) {
            console.log('pembelajaranModal');
            Livewire.emit('cancel')
        })
        anggotaRombelModal.addEventListener('hidden.bs.modal', function (event) {
            console.log('anggotaRombelModal2');
            Livewire.emit('cancel')
        })
        Livewire.on('close-modal', event => {
            console.log('anggotaRombelModal1');
            $('#pembelajaranModal').modal('hide');
            $('#anggotaRombelModal').modal('hide');
        })
    }
</script>
@endsection
@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('vendor-script')
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
