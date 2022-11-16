<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:submit.prevent="save">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center align-middle" rowspan="2">No</th>
                                <th class="text-center align-middle" rowspan="2">Mata Pelajaran</th>
                                <th class="text-center align-middle" rowspan="2">Kelas</th>
                                <th class="text-center" colspan="2">Rasio</th>
                            </tr>
                            <tr>
                                <th class="text-center">Pengetahuan</th>
                                <th class="text-center">Keterampilan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collection as $no => $item)
                            <tr>
                                <td class="text-center">{{$no + 1}}</td>
                                <td>{{$item->nama_mata_pelajaran}}</td>
                                <td class="text-center">{{$item->rombongan_belajar->nama}}</td>
                                <td><input type="text" class="form-control" wire:model="rasio_p.{{$item->pembelajaran_id}}"></td>
                                <td><input type="text" class="form-control" wire:model="rasio_k.{{$item->pembelajaran_id}}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('toastr', event => {
        toastr[event.detail.type](event.detail.message, event.detail.title, {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        });
    })
</script>
@endpush
