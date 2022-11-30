<div>
    <table class="table">
        <thead>
            <tr>
                <th class="text-center align-middle" width="40%">Nama Peserta Didik</th>
                <th class="text-center align-middle" width="15%">NISN</th>
                <th class="text-center align-middle" width="15%">Halaman Depan</th>
                <th class="text-center align-middle" width="15%">Rapor Akademik</th>
                @if($merdeka)
                <th class="text-center align-middle" width="15%">Rapor PROJEK PENGUATAN PROFIL PELAJAR PANCASILA</th>
                @endif
                <th class="text-center align-middle" width="15%">Dokumen Pendukung</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_siswa as $siswa)
            <tr>
                <td>{{$siswa->nama}}</td>
                <td class="text-center">{{$siswa->nisn}}</td>
                <td class="text-center">
                    <a href="{{route('cetak.rapor-cover', ['anggota_rombel_id' => $siswa->anggota_rombel->anggota_rombel_id])}}" target="_blank" class="btn btn-lg btn-icon btn-flat-success">
                        <i class="fa-solid fa-file fa-2xl"></i>
                    </a>
                </td>
                <td class="text-center">
                    <a href="{{route('cetak.rapor-nilai-akhir', ['anggota_rombel_id' => $siswa->anggota_rombel->anggota_rombel_id])}}" target="_blank" class="btn btn-lg btn-icon btn-flat-warning">
                        <i class="fa-solid fa-file-pdf fa-2xl"></i>
                    </a>
                </td>
                @if($merdeka)
                <td class="text-center">
                    <a href="{{route('cetak.rapor-p5', ['anggota_rombel_id' => $siswa->anggota_rombel->anggota_rombel_id])}}" target="_blank" class="btn btn-lg btn-icon btn-flat-warning">
                        <i class="fa-solid fa-file-pdf fa-2xl"></i>
                    </a>
                </td>
                @endif
                <td class="text-center">
                    <a href="{{route('cetak.rapor-pelengkap', ['anggota_rombel_id' => $siswa->anggota_rombel->anggota_rombel_id])}}" target="_blank" class="btn btn-lg btn-icon btn-flat-danger">
                        <i class="fa-regular fa-file-pdf fa-2xl"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
