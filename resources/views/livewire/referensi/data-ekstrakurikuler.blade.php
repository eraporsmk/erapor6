<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nama Ekstrakurikuler</th>
                            <th class="text-center">Nama Pembina</th>
                            <th class="text-center">Prasarana</th>
                            <th class="text-center">Anggota Ekskul</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td>{{$item->nama_ekskul}}</td>
                                <td>{{$item->guru->nama}}</td>
                                <td>{{$item->alamat_ekskul}}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#anggotaEkskulModal" wire:click="viewAnggota('{{$item->rombongan_belajar_id}}')">
                                        Detil <span class="badge bg-secondary">{{$item->rombongan_belajar->anggota_rombel_count}}</span>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button type="button" wire:loading.remove wire:target="syncAnggota('{{$item->rombongan_belajar_id}}')" class="btn btn-danger" wire:click="syncAnggota('{{$item->rombongan_belajar_id}}')"><i class="fa fa-refresh"></i> Sinkron Anggota</button>
                                    <div class="spinner-border text-danger" role="status" wire:loading wire:target="syncAnggota('{{$item->rombongan_belajar_id}}')">
                                        <span class="visually-hidden">Loading...</span>
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
    @include('livewire.referensi.modal.anggota-ekskul')
    @include('components.loader')
</div>
