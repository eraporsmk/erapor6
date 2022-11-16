<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Mata Pelajaran</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Aktifitas Penilaian</th>
                            <th class="text-center">Jenis Sumatif</th>
                            <th class="text-center">Jumlah TP</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td class="align-top">{{$item->pembelajaran->nama_mata_pelajaran}}</td>
                                <td class="align-top">{{$item->rombongan_belajar->nama}}</td>
                                <td class="align-top">{{$item->nama_penilaian}}</td>
                                <td class="align-top">{{$item->teknik_penilaian->nama}}</td>
                                <td class="text-center">{{$item->tp_nilai_count}}</td>
                                <td class="text-center align-top">
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#viewModal" wire:click="getID('{{$item->rencana_penilaian_id}}')" title="Lihat Detil"><i class="fas fa-eye"></i> Detil</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addModal" wire:click="getID('{{$item->rencana_penilaian_id}}')" title="Edit Data"><i class="fas fa-pencil"></i> Edit</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="getID('{{$item->rencana_penilaian_id}}')" title="Hapus Data"><i class="fas fa-trash"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="7">Tidak ada data untuk ditampilkan</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="row justify-content-between mt-2">
                    <div class="col-6">
                        @if($collection->count())
                        <p>Menampilkan {{ $collection->firstItem() }} sampai {{ $collection->firstItem() + $collection->count() - 1 }} dari {{ $collection->total() }} data</p>
                        @endif
                    </div>
                    <div class="col-6">
                        {{ $collection->onEachSide(1)->links('components.custom-pagination-links-view') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.perencanaan.modal.create-kurmer')
    @include('livewire.perencanaan.modal.read-kurmer')
    @include('livewire.perencanaan.modal.copy')
    @include('livewire.perencanaan.modal.delete')
    @include('components.loader')
</div>
@push('scripts')
<script>
    var ids = ['#tingkat', '#rombongan_belajar_id', '#pembelajaran_id'];
    Livewire.on('showModal', event => {
        $.each(ids, function (i, item) {
            $(item).val('');
            $(item).trigger('change');
        })
        $('#addModal').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#addModal').modal('hide');
    })
    window.addEventListener('data_rombongan_belajar', event => {
        $('#rombongan_belajar_id').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $('#pembelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_pembelajaran', event => {
        $('#pembelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $.each(event.detail.data_pembelajaran, function (i, item) {
            $('#pembelajaran_id').append($('<option>', { 
                value: item.pembelajaran_id,
                text : item.nama_mata_pelajaran
            }));
        });
    })
    window.addEventListener('data_cp', event => {
        $('#cp_id').html('<option value="">== Pilih Capaian Pembelajaran ==</option>')
        $.each(event.detail.data_cp, function (i, item) {
            $('#cp_id').append($('<option>', { 
                value: item.cp_id,
                text : item.elemen
            }));
        });
    })
    window.addEventListener('tooltip', event => {
        $('[data-bs-toggle="tooltip"]').tooltip()
    })
</script>
@endpush
