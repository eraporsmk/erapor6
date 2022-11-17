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
                            <th class="text-center">Kode</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Isi Kompetensi/Capaian Pembelajaran</th>
                            <th class="text-center">Kurikulum</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td class="align-top">{{$item->mata_pelajaran->nama_mata_pelajaran}}</td>
                                <td class="align-top">{{$item->id_kompetensi}}</td>
                                <td class="text-center align-top">
                                    @if($item->kelas_10)
                                    10    
                                    @elseif($item->kelas_11)
                                    11
                                    @elseif($item->kelas_12)
                                    12
                                    @else
                                    13
                                    @endif
                                </td>
                                <td class="align-top">{{($item->kompetensi_dasar_alias) ?? $item->kompetensi_dasar}}</td>
                                <td class="text-center align-top">{{$item->kurikulum}}</td>
                                <td class="text-center align-top">{!! ($item->aktif) ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non Aktif</span>' !!}</td>
                                <td class="text-center align-top">
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="#" title="Tambah/Ubah Ringkasan Kompetensi"><i class="fa fa-pencil"></i> Ubah Ringkasan</a></li>
                                            <li><a class="dropdown-item" href="#" title="Hapus Ringkasan Kompetensi"><i class="fas fa-trash"></i> Hapus</a></li>
                                            <li><a class="dropdown-item" href="#" class="confirm_aktif tooltip-left" title="Non Aktifkan"><i class="fa fa-close"></i> Non Aktifkan</a></li>
                                            <li><a class="dropdown-item" href="#" title="Hapus Data Ganda"><i class="fa fa-power-off"></i> Hapus Data Ganda</a></li>
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
    @include('components.loader')
</div>
