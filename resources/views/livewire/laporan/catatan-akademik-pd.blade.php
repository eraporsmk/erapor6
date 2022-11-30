<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center" width="35%">Nama Peserta Didik</th>
                <th class="text-center" width="65%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_siswa as $siswa)
            <tr>
                <td>
                    {{$siswa->nama}}<br>
                    NISN : {{$siswa->nisn}}<br>
                    <span class="badge bg-success">3 (Tiga) Nilai Akhir Terendah</span>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="vertical-align:middle;" rowspan="2">Mata Pelajaran</th>
                                <th class="text-center" colspan="3">Nilai</th>
                            </tr>
                            <tr>
                                <th class="text-center">P</th>
                                <th class="text-center">K</th>
                                <th class="text-center">NA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nilai_rapor[$siswa->anggota_rombel->anggota_rombel_id] as $nilai)
                            <tr>
                                <td>{{$nilai->pembelajaran->nama_mata_pelajaran}}</td>
                                <td class="text-center">{{$nilai->nilai_p}}</td>
                                <td class="text-center">{{$nilai->nilai_k}}</td>
                                <td class="text-center">{{$nilai->total_nilai}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data untuk ditampilkan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
                <td>
                    @if($form)
                    <textarea wire:model.defer="catatan_akademik.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="form-control"></textarea>
                    @else
                    <textarea wire:model.defer="catatan_akademik.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="form-control" disabled></textarea>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
