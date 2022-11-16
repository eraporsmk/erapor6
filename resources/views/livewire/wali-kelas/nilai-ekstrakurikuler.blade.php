<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
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
                <td>{{$anggota_ekskul->rombongan_belajar->kelas_ekskul->guru->nama}}</td>
                <td>
                    {{--($anggota_ekskul->single_nilai_ekstrakurikuler) ? nilai_ekskul($anggota_ekskul->single_nilai_ekstrakurikuler->nilai) : '-'--}}
                    <select wire:model.lazy="nilai_ekskul.{{$item->anggota_rombel->anggota_rombel_id}}.{{$anggota_ekskul->rombongan_belajar->rombongan_belajar_id}}" class="form-select" wire:change="changeNilai('{{$item->anggota_rombel->anggota_rombel_id}}', '{{$anggota_ekskul->rombongan_belajar->rombongan_belajar_id}}')">
                        <option value="">== Pilih Predikat ==</option>
                        <option value="1">Sangat Baik</option>
                        <option value="2">Baik</option>
                        <option value="3">Cukup</option>
                        <option value="4">Kurang</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" id="deskripsi_ekskul" wire:model="deskripsi_ekskul.{{$item->anggota_rombel->anggota_rombel_id}}.{{$anggota_ekskul->rombongan_belajar->rombongan_belajar_id}}">
                </td>
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

            </div>
        </div>
    </div>
</div>
