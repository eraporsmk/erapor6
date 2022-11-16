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
                            <th class="text-center">Butir Sikap</th>
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
                                <td class="align-top">{{$item->ref_sikap->butir_sikap}}</td>
                                <td class="align-top">{{$item->opsi_sikap}}</td>
                                <td class="align-top">{{$item->uraian_sikap}}</td>
                                <td class="text-center align-top">
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editModal" wire:click="getID('{{$item->nilai_sikap_id}}')" title="Edit Data"><i class="fas fa-pencil"></i> Edit</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="delete('{{$item->nilai_sikap_id}}')" title="Hapus Data"><i class="fas fa-trash"></i> Hapus</a></li>
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
</div>
