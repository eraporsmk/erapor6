<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body d-none">
                <h4 class="card-title">Mata Pelajaran yang diampu di Tahun Pelajaran {{session('semester_id')}}</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">No</th>
                            <th class="text-center align-middle">Mata Pelajaran</th>
                            <th class="text-center align-middle">Rombel</th>
                            <th class="text-center align-middle">Wali Kelas</th>
                            <th class="text-center align-middle">Jumlah Peserta Didik</th>
                            <th class="text-center">Generate Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mapel_diampu_merdeka as $key => $item)
                            <tr>
                                <td class="text-center">{{$key + 1}}</td>
                                <td>{{$item->nama_mata_pelajaran}}</td>
                                <td>{{$item->rombongan_belajar->nama}}</td>
                                <td>{{($item->rombongan_belajar->wali_kelas) ? $item->rombongan_belajar->wali_kelas->nama_lengkap : '-'}}</td>
                                <td class="text-center">{{$item->rombongan_belajar->anggota_rombel_count}}</td>
                                <td class="text-center">
                                    @if($item->rencana_penilaian_count)
                                    <button class="btn btn-sm btn-{{($item->nilai_akhir_count) ? 'danger' : 'success'}}" wire:click="generateNilai('{{$item->pembelajaran_id}}', 4)">Generate Nilai</button>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data untuk ditampilkan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-body">
                <h4 class="card-title">Mata Pelajaran yang diampu di Tahun Pelajaran {{session('semester_id')}}</h4>
                <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="mapel-kurtilas-tab" data-bs-toggle="tab" href="#mapel-kurtilas" role="tab" aria-controls="mapel-kurtilas" aria-selected="true">Kurikulum 2013 REV</a
                      >
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="mapel-merdeka-tab" data-bs-toggle="tab" href="#mapel-merdeka" role="tab" aria-controls="mapel-merdeka" aria-selected="true">Kurikulum Merdeka</a>
                    </li>
                </ul>
                <div class="tab-content pt-1">
                    <div class="tab-pane active" id="mapel-kurtilas" role="tabpanel" aria-labelledby="mapel-kurtilas-tab">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">No</th>
                                    <th rowspan="2" class="text-center align-middle">Mata Pelajaran</th>
                                    <th rowspan="2" class="text-center align-middle">Rombel</th>
                                    <th rowspan="2" class="text-center align-middle">Wali Kelas</th>
                                    <th colspan="2" class="text-center">Generate Nilai</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Pengetahuan</th>
                                    <th class="text-center">Keterampilan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mapel_diampu_kurtilas as $key => $item)
                                    <tr>
                                        <td class="text-center">{{$key + 1}}</td>
                                        <td>{{$item->nama_mata_pelajaran}}</td>
                                        <td>{{$item->rombongan_belajar->nama}}</td>
                                        <td>{{($item->rombongan_belajar->wali_kelas) ? $item->rombongan_belajar->wali_kelas->nama_lengkap : '-'}}</td>
                                        <td class="text-center">
                                            @if($item->pk_dinilai || $item->pengetahuan_dinilai)
                                            <button class="btn btn-sm btn-{{($item->na_pengetahuan || $item->na_pk) ? 'danger' : 'success'}}" wire:click="generateNilai('{{$item->pembelajaran_id}}', {{($item->pk_dinilai) ? 3 : 1}})">Generate Nilai</button>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($item->keterampilan_dinilai)
                                            <button class="btn btn-sm btn-{{($item->na_keterampilan) ? 'danger' : 'success'}}" wire:click="generateNilai('{{$item->pembelajaran_id}}', 2)">Generate Nilai</button>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data untuk ditampilkan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="mapel-merdeka" role="tabpanel" aria-labelledby="mapel-merdeka-tab">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Mata Pelajaran</th>
                                    <th class="text-center align-middle">Rombel</th>
                                    <th class="text-center align-middle">Wali Kelas</th>
                                    <th class="text-center align-middle">Jumlah Peserta Didik</th>
                                    <th class="text-center">Generate Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mapel_diampu_merdeka as $key => $item)
                                    <tr>
                                        <td class="text-center">{{$key + 1}}</td>
                                        <td>{{$item->nama_mata_pelajaran}}</td>
                                        <td>{{$item->rombongan_belajar->nama}}</td>
                                        <td>{{($item->rombongan_belajar->wali_kelas) ? $item->rombongan_belajar->wali_kelas->nama_lengkap : '-'}}</td>
                                        <td class="text-center">{{$item->rombongan_belajar->anggota_rombel_count}}</td>
                                        <td class="text-center">
                                            @if($item->rencana_penilaian_count)
                                            <button class="btn btn-sm btn-{{($item->nilai_akhir_count) ? 'danger' : 'success'}}" wire:click="generateNilai('{{$item->pembelajaran_id}}', 4)">Generate Nilai</button>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data untuk ditampilkan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @role('wali', session('semester_id'))
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Anda adalah Wali Kelas Rombongan Belajar {{($rombongan_belajar) ? $rombongan_belajar->nama : '-'}}</h4>
                <h5 class="card-title">Daftar Mata Pelajaran di Rombongan Belajar {{($rombongan_belajar) ? $rombongan_belajar->nama : '-'}}</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Mata Pelajaran</th>
                            <th class="text-center align-middle">Guru Mata Pelajaran</th>
                            <th class="text-center align-middle">Peserta Didik Dinilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($rombongan_belajar)
                        @forelse ($rombongan_belajar->pembelajaran as $item)
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$item->nama_mata_pelajaran}}</td>
                                <td>{{($item->pengajar) ? $item->pengajar->nama_lengkap : $item->guru->nama_lengkap}}</td>
                                <td class="text-center">{{$item->anggota_rombel_count}}</td>
                                <!--
                                <td class="text-center">{{($item->pk_dinilai) ? $item->pk_dinilai : $item->pengetahuan_dinilai}}</td>
                                <td class="text-center">{{$item->keterampilan_dinilai}}</td>
                                <td class="text-center">
                                    @if($item->pk_dinilai || $item->pengetahuan_dinilai)
                                    <button class="btn btn-sm btn-{{($item->na_pengetahuan || $item->na_pk) ? 'danger' : 'success'}}" wire:click="generateNilai('{{$item->pembelajaran_id}}', {{($item->pk_dinilai) ? 3 : 1}})">Generate Nilai</button>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->keterampilan_dinilai)
                                    <button class="btn btn-sm btn-{{($item->na_keterampilan) ? 'danger' : 'success'}}" wire:click="generateNilai('{{$item->pembelajaran_id}}', 2)">Generate Nilai</button>
                                    @else
                                    -
                                    @endif
                                </td>-->
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data untuk ditampilkan</td>
                            </tr>
                        @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endrole
    </div>
    @include('components.loader')
</div>
