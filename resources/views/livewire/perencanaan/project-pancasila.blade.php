<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Tema</th>
                            <th class="text-center">Nama Projek</th>
                            <th class="text-center">Deskripsi</th>
                            <th class="text-center">Jumlah Sub Elemen</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td class="align-top">{{$item->rombongan_belajar->nama}}</td>
                                <td class="align-top">{{$item->pembelajaran->nama_mata_pelajaran}}</td>
                                <td class="align-top">{{$item->nama}}</td>
                                <td class="align-top">{{$item->deskripsi}}</td>
                                <td class="text-center">{{$item->aspek_budaya_kerja_count}}</td>
                                <td class="text-center align-top">
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#viewModal" wire:click="getID('{{$item->rencana_budaya_kerja_id}}', 'detil')" title="Lihat Detil"><i class="fas fa-eye"></i> Detil</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editModal" wire:click="getID('{{$item->rencana_budaya_kerja_id}}', 'edit')" title="Edit Data"><i class="fas fa-pencil"></i> Edit</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="getID('{{$item->rencana_budaya_kerja_id}}', 'delete')" title="Hapus Data"><i class="fas fa-trash"></i> Hapus</a></li>
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
    @include('livewire.perencanaan.modal.create-p5')
    @include('livewire.perencanaan.modal.read-p5')
    @include('livewire.perencanaan.modal.edit-p5')
    @include('livewire.perencanaan.modal.delete-p5')
    @include('components.loader')
    {{--
    
    
    @include('livewire.perencanaan.modal.copy')
    @include('livewire.perencanaan.modal.delete')
    --}}
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
    Livewire.on('detilModal', event => {
        $('#detilModal').modal('show');
    })
    Livewire.on('editModal', event => {
        $('#editModal').modal('show');
    })
    Livewire.on('deleteModal', event => {
        $('#deleteModal').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#addModal').modal('hide');
        $('#detilModal').modal('hide');
        $('#editModal').modal('hide');
        $('#deleteModal').modal('hide');
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
        console.log(event.detail);
        $('#pembelajaran_id').html('<option value="">== Pilih Mata Tema ==</option>')
        $.each(event.detail.data_pembelajaran, function (i, item) {
            $('#pembelajaran_id').append($('<option>', { 
                value: item.pembelajaran_id,
                text : item.nama_mata_pelajaran
            }));
        });
    })
</script>
@endpush
