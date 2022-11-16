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
                <td>{{$siswa->nama}}</td>
                <td>
                    @role('wali', session('semester_id'))
                    <textarea wire:ignore wire:model="catatan_akademik.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="form-control"></textarea>
                    @else
                    {{$catatan_akademik[$siswa->anggota_rombel->anggota_rombel_id]}}
                    @endrole
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
