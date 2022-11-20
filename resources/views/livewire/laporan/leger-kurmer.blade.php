<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nama Peserta Didik</th>
                            <th class="text-center">NISN</th>
                            @foreach ($data_pembelajaran as $pembelajaran)
                            <th class="text-center">{{$pembelajaran->nama_mata_pelajaran}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_siswa as $item)
                        <tr>
                            <td>{{$item->nama}}</td>
                            <td class="text-center">{{$item->nisn}}</td>
                            @foreach ($data_pembelajaran as $pembelajaran)
                            <?php
                            $nilai = $pembelajaran->nilai_akhir_kurmer()->where('anggota_rombel_id', $item->anggota_rombel->anggota_rombel_id)->first();
                            ?>
                            <td class="text-center">{{($nilai) ? $nilai->nilai : '-'}}</td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="{{($data_pembelajaran) ? ($data_pembelajaran->count() + 2) : 2}}">Tidak ada data untuk ditampilkan</td>
                        </tr> 
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
