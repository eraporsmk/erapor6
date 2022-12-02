<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Nama Peserta Didik</th>
                <th class="text-center">Nama Eskul</th>
                <th class="text-center">Pembina</th>
                <th class="text-center">Predikat</th>
                <th class="text-center">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data_siswa as $item)
            <tr>
                <td rowspan="{{$item->anggota_rombel->anggota_ekskul->count() + 1}}" class="align-middle">{{$item->nama}}</td>
            </tr>
            @foreach ($item->anggota_rombel->anggota_ekskul as $anggota_ekskul)
            <tr>
                <td>{{$anggota_ekskul->rombongan_belajar->kelas_ekskul->nama_ekskul}}</td>
                <td>{{$anggota_ekskul->rombongan_belajar->kelas_ekskul->guru->nama_lengkap}}</td>
                <td>{{($anggota_ekskul->single_nilai_ekstrakurikuler) ? nilai_ekskul($anggota_ekskul->single_nilai_ekstrakurikuler->nilai) : '-'}}</td>
                <td>{{($anggota_ekskul->single_nilai_ekstrakurikuler) ? $anggota_ekskul->single_nilai_ekstrakurikuler->deskripsi_ekskul : '-'}}</td>
            </tr>
            @endforeach
            @empty
            <tr>
                <td class="text-center" colspan="5">Tidak ada untuk ditampilkan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
