<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                @if($rombel_empat_tahun)
                <th class="text-center" width="40%">Nama Peserta Didik</th>
                <th class="text-center" width="30%">Status Kenaikan</th>
                <th class="text-center" width="30%">Ke Kelas</th>
                @elseif($tingkat >= 12)
                <th class="text-center" width="60%">Nama Peserta Didik</th>
                <th class="text-center" width="40%">Status Kelulusan</th>
                @else
                <th class="text-center" width="40%">Nama Peserta Didik</th>
                <th class="text-center" width="30%">Status Kenaikan</th>
                <th class="text-center" width="30%">Ke Kelas</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($data_siswa as $siswa)
            <tr>
                <td>{{$siswa->nama}}</td>
                <td>
                    @role('wali', session('semester_id'))
                    <div wire:ignore>
                        @if($rombel_empat_tahun)
                        <select wire:model="status.{{$siswa->anggota_rombel->anggota_rombel_id}}" id="status" class="form-select">
                            <option value="">== Pilih Status Kenaikan ==</option>
                            <option value="1">Naik Ke Kelas</option>
                            <option value="2">Tidak Naik</option>
                        </select>
                        @elseif($tingkat >= 12)
                        <select wire:model="status.{{$siswa->anggota_rombel->anggota_rombel_id}}" id="status" class="form-select">
                            <option value="">== Pilih Status Kelulusan ==</option>
                            <option value="3">Lulus</option>
                            <option value="4">Tidak Lulus</option>
                        </select>
                        @else
                        <select wire:model="status.{{$siswa->anggota_rombel->anggota_rombel_id}}" id="status" class="form-select">
                            <option value="">== Pilih Status Kenaikan ==</option>
                            <option value="1">Naik Ke Kelas</option>
                            <option value="2">Tidak Naik</option>
                        </select>
                        @endif
                    </div>
                    @else
                    {{$status[$siswa->anggota_rombel->anggota_rombel_id]}}
                    @endrole
                </td>
                @if($tingkat < 12 || $rombel_empat_tahun)
                <td>
                    <input type="text" wire:model.defer="nama_kelas.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="form-control">
                    <input type="hidden" wire:model="rombongan_belajar_id.{{$siswa->anggota_rombel->rombongan_belajar_id}}" class="form-control">
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
