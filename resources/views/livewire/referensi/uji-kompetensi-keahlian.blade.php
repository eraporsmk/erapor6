<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="20%">Kompetensi Keahlian</th>
                            <th width="10%">Nomor Paket</th>
                            <th width="50%">Nama Paket</th>
                            <th width="5%" class="text-center">Jml Unit</th>
                            <th width="5%" class="text-center">Status</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <?php
                            $ganti_status = ($item->status) ? 'Non Aktifkan' : 'Aktifkan';
			                $power_status = ($item->status) ? 'fa-power-off' : 'fa-check';
                            ?>
                            <tr>
                                <td>{{$item->jurusan->nama_jurusan}}</td>
                                <td class="text-center">{{$item->nomor_paket}}</td>
                                <td>{{$item->nama_paket_id}}</td>
                                <td class="text-center">{{$item->unit_ukk->count()}}</td>
                                <td>{!! status_label($item->status) !!}</td>
                                <td>
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="btnGroupDrop">
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getUnit('{{$item->paket_ukk_id}}', 'add')"><i class="fas fa-plus"></i> Tambah Unit</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getUnit('{{$item->paket_ukk_id}}', 'detil')"><i class="fa fa-search"></i> Detil</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getUnit('{{$item->paket_ukk_id}}','status')" data-status="{{$item->status}}"><i class="fas {{$power_status}}"></i> {{$ganti_status}}</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getUnit('{{$item->paket_ukk_id}}', 'edit')"><i class="fa fa-pencil"></i> Ubah</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="6">Tidak ada data untuk ditampilkan</td>
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
    @include('livewire.referensi.modal.tambah-ukk')
    @include('livewire.referensi.modal.add-ukk')
    @include('livewire.referensi.modal.detil-ukk')
    @include('livewire.referensi.modal.edit-ukk')
    @include('components.loader')
</div>
@push('scripts')
<script>
    Livewire.on('tambahModal', event => {
        $('#tambahModal').modal('show');
    })
    Livewire.on('addModal', event => {
        $('#addModal').modal('show');
    })
    Livewire.on('detilModal', event => {
        $('#detilModal').modal('show');
    })
    Livewire.on('editModal', event => {
        $('#editModal').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#tambahModal').modal('hide');
        $('#addModal').modal('hide');
        $('#detilModal').modal('hide');
        $('#editModal').modal('hide');
    })
    window.addEventListener('data_kurikulum', event => {
        console.log(event.detail);
        $('#kurikulum_id').html('<option value="">== Pilih Kurikulum ==</option>')
        $.each(event.detail.data_kurikulum, function (i, item) {
            $('#kurikulum_id').append($('<option>', { 
                value: item.kurikulum_id,
                text : item.nama_kurikulum
            }));
        });
    })
</script> 
@endpush
