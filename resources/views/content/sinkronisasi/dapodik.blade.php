@extends('layouts/contentLayoutMaster')

@section('title', 'Ambil Data Dapodik')

@section('content')
@livewire('sinkronisasi.dapodik')
@endsection
@section('page-script')
<script>
    Livewire.on('clickSync', function(e){
        Livewire.emit('showProgress')
    })
    document.addEventListener("DOMContentLoaded", () => {
        /*Livewire.hook('component.initialized', (component) => {
            console.log(1);
        })
        Livewire.hook('element.initialized', (el, component) => {
            console.log(2);
        })
        Livewire.hook('element.updating', (fromEl, toEl, component) => {
            console.log(3);
        })
        Livewire.hook('element.updated', (el, component) => {
            console.log(4);
        })*/
        //Livewire.hook('element.removed', (el, component) => {
            //console.log(5);
            //Livewire.emit('sinkronisasi')
        //})
        /*Livewire.hook('message.sent', (message, component) => {
            console.log(6);
        })
        Livewire.hook('message.failed', (message, component) => {
            console.log(7);
        })
        Livewire.hook('message.received', (message, component) => {
            console.log(8);
        })
        Livewire.hook('message.processed', (message, component) => {
            console.log(9);
        })*/
    });
</script>
@endsection
