<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('livewire.formulir-guru')
                @if($show)
                <h2>Rekapitulasi Penilaian Mata Pelajaran {{$nama_mapel}}</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center">Nilai Pengetahuan</th>
                            <th class="text-center">Nilai Keterampilan</th>
                            <th class="text-center">Nilai Akhir</th>
                            <th class="text-center">Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data_siswa as $siswa)
                        <?php 
                        $nilai_pengetahuan = '-';
                        $nilai_keterampilan = '-';
                        $nilai_akhir_pengetahuan = 0;
                        $nilai_akhir_keterampilan = 0;
                        if($siswa->anggota_rombel->nilai_akhir_pengetahuan){
                            $nilai_akhir_pengetahuan = $siswa->anggota_rombel->nilai_akhir_pengetahuan->nilai;
                        }
                        if($siswa->anggota_rombel->nilai_akhir_keterampilan){
                            $nilai_akhir_keterampilan = $siswa->anggota_rombel->nilai_akhir_keterampilan->nilai;
                        }
                        $nilai_akhir = (($nilai_akhir_pengetahuan * $rasio_p) + ($nilai_akhir_keterampilan * $rasio_k)) / 100;
                        $nilai_akhir = ($nilai_akhir) ? number_format($nilai_akhir,0) : 0;
                        ?>
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$siswa->nama}}</td>
                            <td class="text-center">{{$siswa->nisn}}</td>
                            <td class="text-center">{{$nilai_akhir_pengetahuan}}</td>
                            <td class="text-center">{{$nilai_akhir_keterampilan}}</td>
                            <td class="text-center">{{$nilai_akhir}}</td>
                            <td class="text-center">{{konversi_huruf(get_kkm($kelompok_id, $kkm), $nilai_akhir, $mapel_produktif)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
