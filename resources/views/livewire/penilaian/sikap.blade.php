<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nama PD</th>
                            <th class="text-center">Rombel</th>
                            <th class="text-center">Dimensi Sikap</th>
                            <th class="text-center">Elemen Sikap</th>
                            <th class="text-center">Opsi Sikap</th>
                            <th class="text-center">Uraian Sikap</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td class="align-top">{{$item->anggota_rombel->peserta_didik->nama}}</td>
                                <td class="align-top">{{$item->anggota_rombel->rombongan_belajar->nama}}</td>
                                <td class="align-top">{{$item->budaya_kerja->aspek}}</td>
                                <td class="align-top">{{$item->elemen_budaya_kerja->elemen}}</td>
                                <td class="align-top text-center">{{($item->opsi_id == 1) ? 'Positif' : 'Negatif'}}</td>
                                <td class="align-top">{{$item->deskripsi}}</td>
                                <td class="text-center align-top">
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getID('{{$item->nilai_budaya_kerja_id}}')" title="Edit Data"><i class="fas fa-pencil"></i> Edit</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="delete('{{$item->nilai_budaya_kerja_id}}')" title="Hapus Data"><i class="fas fa-trash"></i> Hapus</a></li>
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
    @include('livewire.penilaian.modal-sikap.edit')
    @include('components.loader')
</div>
@push('scripts')
<script>
    Livewire.on('show-modal', event => {
        $('#editModal').modal('show')
    })
    Livewire.on('close-modal', event => {
        $('#editModal').modal('hide')
    })
</script>
@endpush