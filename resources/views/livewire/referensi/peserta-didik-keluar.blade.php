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
                            <th class="text-center">L/P</th>
                            <th class="text-center">Tempat, Tanggal Lahir</th>
                            <th class="text-center">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td>{{$item->nama}}</td>
                                <td class="text-center">{{$item->jenis_kelamin}}</td>
                                <td>{{$item->tempat_lahir}}, {{$item->tanggal_lahir}}</td>
                                <td>{{$item->email}}</td>
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
    @include('components.loader')
</div>
