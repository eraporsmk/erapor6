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
                            <th class="text-center">Fase</th>
                            <th class="text-center">Elemen</th>
                            <th class="text-center">Deskripsi</th>
                            <th class="text-center">Jumlah Tujuan Pembelajaran</th>
                            <!--th class="text-center">Status</th>
                            <th class="text-center">Aksi</th-->
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
                                <td class="align-top">{{$item->pembelajaran->nama_mata_pelajaran}}</td>
                                <td class="text-center align-top">
                                    {{$item->fase}}
                                </td>
                                <td class="align-top">{{$item->elemen}}</td>
                                <td class="align-top">{{$item->deskripsi}}</td>
                                <td class="text-center align-top">{{$item->tp_count}}</td>
                                {{--
                                <td class="text-center align-top">
                                    @if($item->aktif)
                                    <button class="btn btn-sm btn-success" wire:click="getId('{{$item->kompetensi_dasar_id}}', 'aktif')">Aktif</button>
                                    @else
                                    <button class="btn btn-sm btn-danger" wire:click="getId('{{$item->kompetensi_dasar_id}}', 'aktif')">Non Aktif</button>
                                    @endif
                                </td>
                                <td class="text-center align-top">
                                    {!! ($item->aktif) ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non Aktif</span>' !!}
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getId('{{$item->kompetensi_dasar_id}}', 'edit')" title="Tambah/Ubah Ringkasan Kompetensi"><i class="fa fa-pencil"></i> Ubah Deskripsi</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getId('{{$item->kompetensi_dasar_id}}', 'aktif')" class="confirm_aktif tooltip-left" title="{{$title_aktif}}"><i class="fas {{$icon_aktif}}"></i> {{$title_aktif}}</a></li>
                                        </ul>
                                    </div>
                                </td>
                                --}}
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="8">Tidak ada data untuk ditampilkan</td>
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
</div>
@push('scripts')
<script>
    Livewire.on('editCP', event => {
        $('#editCP').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#editCP').modal('hide');
    })
</script>
@endpush
