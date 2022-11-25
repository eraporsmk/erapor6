@extends('layouts/contentLayoutMaster')

@section('title', 'Tambah Data KD')

@section('content')
@livewire('referensi.tambah-kompetensi-dasar')
@endsection
@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
@section('page-script')
<script>
    
    /*document.addEventListener("livewire:load", () => {
        $('.select').select2({
            allowClear: true
        })
        Livewire.hook('message.processed', (message, component) => {
            //$('#rombongan_belajar_id').select2()
            console.log(message.response.serverMemo.dataMeta.modelCollections.data_rombongan_belajar);
            //console.log(component);
        }); 
    });*/
  </script>
@endsection
