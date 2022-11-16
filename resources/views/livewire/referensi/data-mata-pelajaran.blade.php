<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data_mata_pelajaran as $mata_pelajaran)
                        <tr>
                            <td>{{$mata_pelajaran->mata_pelajaran_id}}</td>
                            <td>{{$mata_pelajaran->nama}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row justify-content-between mt-2">
                    <div class="col-6">
                        @if($data_mata_pelajaran->count())
                        <p>Showing {{ $data_mata_pelajaran->firstItem() }} to {{ $data_mata_pelajaran->firstItem() + $data_mata_pelajaran->count() - 1 }} of {{ $data_mata_pelajaran->total() }} items</p>
                        @endif
                    </div>
                    <div class="col-6">
                        {{ $data_mata_pelajaran->onEachSide(1)->links('components.custom-pagination-links-view') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
