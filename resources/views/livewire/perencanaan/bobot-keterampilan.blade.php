<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:submit.prevent="save">
                <div class="card-body">
                    <table class="table table-bordered {{table_striped()}}">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Teknik Penilaian</th>
                                <th class="text-center align-middle">Kelas</th>
                                <th class="text-center">Mata Pelajaran</th>
                                <th class="text-center">Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collection as $no => $item)
                            <tr>
                                <td class="text-center">{{$no + 1}}</td>
                                <td>{{$item->metode->nama}}</td>
                                <td>{{$item->pembelajaran->rombongan_belajar->nama}}</td>
                                <td>
                                    {{$item->pembelajaran->nama_mata_pelajaran}}
                                    <input type="hidden" class="form-control" wire:model="pembelajaran_id.{{$item->bobot_keterampilan_id}}">
                                </td>
                                <td><input type="text" class="form-control" wire:model="bobot.{{$item->bobot_keterampilan_id}}"></td>
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
