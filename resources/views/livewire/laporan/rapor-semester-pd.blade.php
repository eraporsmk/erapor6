<div>
    <table class="table">
        <thead>
            <tr>
                <th class="text-center align-middle" width="40%">Nama Peserta Didik</th>
                <th class="text-center align-middle" width="15%">Lihat Nilai</th>
                <th class="text-center align-middle" width="15%">Halaman Depan</th>
                <th class="text-center align-middle" width="15%">Rapor Akademik & Karakter</th>
                <th class="text-center align-middle" width="15%">Rapor P5</th>
                <th class="text-center align-middle" width="15%">Dokumen Pendukung</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_siswa as $siswa)
            <tr>
                <td>{{$siswa->nama}}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-lg btn-icon btn-flat-primary" wire:click="preview('{{$siswa->anggota_rombel->anggota_rombel_id}}')">
                        <i class="fa-solid fa-search fa-2xl"></i>
                    </button>
                </td>
                <td class="text-center">
                    <a href="{{url('/cetak/rapor-cover/'.$siswa->anggota_rombel->anggota_rombel_id)}}" target="_blank" class="btn btn-lg btn-icon btn-flat-success">
                        <i class="fa-solid fa-file fa-2xl"></i>
                    </a>
                </td>
                <td class="text-center">
                    @if(Illuminate\Support\Str::of($rombongan_belajar->kurikulum)->contains('Merdeka'))
                    <a href="{{route('cetak.rapor-nilai-akhir', ['anggota_rombel_id' => $siswa->anggota_rombel->anggota_rombel_id])}}" target="_blank" class="btn btn-lg btn-icon btn-flat-warning">
                        <i class="fa-solid fa-file-pdf fa-2xl"></i>
                    </a>
                    @else
                    <a href="{{url('/cetak/rapor-semester/'.$siswa->anggota_rombel->anggota_rombel_id)}}" target="_blank" class="btn btn-lg btn-icon btn-flat-warning">
                        <i class="fa-solid fa-file-pdf fa-2xl"></i>
                    </a>
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{route('cetak.rapor-p5', ['anggota_rombel_id' => $siswa->anggota_rombel->anggota_rombel_id])}}" target="_blank" class="btn btn-lg btn-icon btn-flat-warning">
                        <i class="fa-solid fa-file-pdf fa-2xl"></i>
                    </a>
                </td>
                <td class="text-center">
                    <a href="{{url('/cetak/rapor-pelengkap/'.$siswa->anggota_rombel->anggota_rombel_id)}}" target="_blank" class="btn btn-lg btn-icon btn-flat-danger">
                        <i class="fa-regular fa-file-pdf fa-2xl"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
