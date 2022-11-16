<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center" width="40%">Nama Peserta Didik</th>
                <th class="text-center" width="30%">Status Kenaikan</th>
                <th class="text-center" width="30%">Ke Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_siswa as $siswa)
            <tr>
                <td>{{$siswa->nama}}</td>
                <td>
                    @role('wali', session('semester_id'))
                    <div wire:ignore>
                        <select wire:model="status.{{$siswa->anggota_rombel->anggota_rombel_id}}" id="status" class="form-select">
                            <option value="">== Pilih Status Kenaikan==</option>
                            <option value="1">Naik Ke Kelas</option>
                            <option value="2">Tidak Naik</option>
                        </select>
                    </div>
                    @else
                    {{$status[$siswa->anggota_rombel->anggota_rombel_id]}}
                    @endrole
                </td>
                <td>
                    <input type="text" wire:model="nama_kelas.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="form-control">
                    <input type="hidden" wire:model="rombongan_belajar_id.{{$siswa->anggota_rombel->rombongan_belajar_id}}" class="form-control">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
