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
                            <th class="text-center">NISN</th>
                            <th class="text-center">L/P</th>
                            <th class="text-center">Tempat, Tanggal Lahir</th>
                            <th class="text-center">Agama</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Detil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td>{{$item->nama}}</td>
                                <td class="text-center">{{$item->nisn}}</td>
                                <td class="text-center">{{$item->jenis_kelamin}}</td>
                                <td>{{$item->tempat_lahir}}, {{$item->tanggal_lahir}}</td>
                                <td>{{$item->agama->nama}}</td>
                                <td>{{($item->anggota_rombel) ? $item->anggota_rombel->rombongan_belajar->nama : '-'}}</td>
                                <td class="text-center"><button class="btn btn-info btn-sm" wire:click="getID('{{$item->peserta_didik_id}}')">Detil</button></td>
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
    @include('livewire.referensi.modal.detil-pd')
    @include('components.loader')
</div>
@push('scripts')
<script>
    Livewire.on('show-modal', event => {
        $('#detilPD').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#detilPD').modal('hide');
    })
</script>
@endpush
