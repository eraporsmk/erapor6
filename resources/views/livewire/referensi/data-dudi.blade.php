<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Bidang Usaha</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">Jml Aktifitas</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td>{{$item->nama}}</td>
                                <td>{{$item->nama_bidang_usaha}}</td>
                                <td>{{$item->alamat_jalan}}</td>
                                <td class="text-center">{{$item->akt_pd_count}}</td>
                                <td class="text-center"><button class="btn btn-sm btn-warning" wire:click="getID('{{$item->dudi_id}}')" data-bs-toggle="modal" data-bs-target="#detilModal"><i class="fas fa-eye"></i> Detil</button></td>
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
    @include('livewire.referensi.modal.detil-dudi')
    @include('livewire.referensi.modal.anggota-akt-pd')
    @include('components.loader')
</div>
