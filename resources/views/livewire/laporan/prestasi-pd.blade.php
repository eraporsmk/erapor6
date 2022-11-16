<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nama Peserta Didik</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Jenis Prestasi</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td>{{$item->peserta_didik->nama}}</td>
                                <td class="text-center">{{$item->anggota_rombel->rombongan_belajar->nama}}</td>
                                <td>{{$item->jenis_prestasi}}</td>
                                <td>{{$item->keterangan_prestasi}}</td>
                                <td class="text-center">
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getId('{{$item->prestasi_id}}', 'edit')" title="Edit Data"><i class="fa fa-pencil"></i> Edit Data</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getId('{{$item->prestasi_id}}', 'delete')" title="Hapus Data"><i class="fas fa-trash"></i> Hapus Data</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="4">Tidak ada data untuk ditampilkan</td>
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
    @include('livewire.laporan.modal.prestasi-pd')
    @include('livewire.laporan.modal.edit-prestasi-pd')
</div>
@push('scripts')
<script>
    Livewire.on('showModal', event => {
        $('#editModal').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#addModal').modal('hide');
        $('#editModal').modal('hide');
    })
    Livewire.on('confirmed', event => {
        $('#jenis_prestasi').val(null);
        $('#jenis_prestasi').trigger('change');
        $('#anggota_rombel_id').val(null);
        $('#anggota_rombel_id').trigger('change');
    })
    window.addEventListener('anggota_rombel_id', event => {
        console.log(event.detail.anggota_rombel_id);
        $('#anggota_rombel_id').val(event.detail.anggota_rombel_id)
    });
    window.addEventListener('jenis_prestasi', event => {
        console.log(event.detail.jenis_prestasi);
        $('#jenis_prestasi').val(event.detail.jenis_prestasi)
    })
</script>
@endpush
