<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @if (session()->has('message'))
                    <div class="alert alert-success" role="alert">
                        <div class="alert-body">{{ session('message') }}</div>
                    </div>
                @endif
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Mata Pelajaran</th>
                            <th class="text-center">Capaian Pembelajaran/Kompetensi Dasar</th>
                            <th class="text-center">Fase/Kelas</th>
                            <th class="text-center">Tujuan Pembelajaran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <?php
                            if($item->aktif){
                                $icon_aktif 	= 'fa-close';
                                $title_aktif	= 'Non Aktifkan';
                            } else {
                                $icon_aktif 	= 'fa-check';
                                $title_aktif	= 'Aktifkan';
                            }
                            ?>
                            <tr>
                                @if($item->cp)
                                <td class="align-top">
                                    {{$item->cp->mata_pelajaran->nama}}
                                </td>
                                <td class="align-top">{{$item->cp->elemen}}</td>
                                <td class="text-center align-top">
                                    {{$item->cp->fase}}
                                </td>
                                @endif
                                @if($item->kd)
                                <td class="align-top">
                                    {{$item->kd->mata_pelajaran->nama}}
                                </td>
                                <td class="align-top">
                                    {{$item->kd->kompetensi_dasar}}
                                </td>
                                <td class="text-center align-top">
                                    {{tingkat_kelas($item->kd->kelas_10, $item->kd->kelas_11, $item->kd->kelas_12, $item->kd->kelas_13)}}
                                </td>
                                @endif
                                <td class="align-top">{{$item->deskripsi}}</td>
                                <td class="text-center align-top">
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getId('{{$item->tp_id}}', 'edit')" title="Ubah Data"><i class="fa fa-pencil"></i> Ubah Data</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getId('{{$item->tp_id}}', 'hapus')" class="confirm_aktif tooltip-left" title="Hapus Data"><i class="fas fa-trash"></i> Hapus Data</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="5">Tidak ada data untuk ditampilkan</td>
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
    @include('components.loader')
    <div wire:ignore.self class="modal fade" id="editTP" tabindex="-1" aria-labelledby="editTPLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTPLabel">Ubah Data Tujuan Pembelajaran (TP)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea wire:model="deskripsi" class="form-control" placeholder="Edit Tujuan Pembelajaran"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:target="perbaharui" wire:loading.remove>Tutup</button>
                    <div class="spinner-border text-primary" role="status" wire:loading wire:target="perbaharui">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="perbaharui" wire:target="perbaharui" wire:loading.remove>Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    Livewire.on('editTP', event => {
        $('#editTP').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#editTP').modal('hide');
    })
</script>
@endpush
