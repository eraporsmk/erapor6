<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Nama Peserta Didik</th>
                <th class="text-center">Alamat</th>
                <th class="text-center">Skala Kesesuaian dengan Kompetensi Keahlian (1-10)</th>
                <th class="text-center">Lamanya (bulan)</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data_siswa as $urut => $siswa)
            <tr>
                <td class="align-top">{{$siswa->nama}}</td>
                <td class="align-top">
                    @role('wali', session('semester_id'))
                    <input type="text" class="form-control" wire:model.defer="lokasi_prakerin.{{$siswa->anggota_rombel->anggota_rombel_id}}" id="lokasi_prakerin">
                    @if($errors->has('lokasi_prakerin.'.$siswa->anggota_rombel->anggota_rombel_id))
                    <span class="text-danger">{{ $errors->first('lokasi_prakerin.'.$siswa->anggota_rombel->anggota_rombel_id) }}</span>
                    @endif
                    @else
                    {{$lokasi_prakerin[$siswa->anggota_rombel->anggota_rombel_id]}}
                    @endrole
                </td>
                <td class="align-top">
                    @role('wali', session('semester_id'))
                        <select id="skala_{{$siswa->anggota_rombel->anggota_rombel_id}}" class="form-select" wire:model.defer="skala.{{$siswa->anggota_rombel->anggota_rombel_id}}" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Skala ==" data-search-off="true">
                            <option value="">== Pilih Skala ==</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                        @if($errors->has('skala.'.$siswa->anggota_rombel->anggota_rombel_id))
                        <span class="text-danger">{{ $errors->first('skala.'.$siswa->anggota_rombel->anggota_rombel_id) }}</span>
                        @endif
                    @else
                    {{$skala[$siswa->anggota_rombel->anggota_rombel_id]}}
                    @endrole
                </td>
                <td class="align-top">
                    @role('wali', session('semester_id'))
                    <input type="number" class="form-control" wire:model.defer="lama_prakerin.{{$siswa->anggota_rombel->anggota_rombel_id}}">
                    @if($errors->has('lama_prakerin.'.$siswa->anggota_rombel->anggota_rombel_id))
                        <span class="text-danger">{{ $errors->first('lama_prakerin.'.$siswa->anggota_rombel->anggota_rombel_id) }}</span>
                    @endif
                    @else
                    {{$lama_prakerin[$siswa->anggota_rombel->anggota_rombel_id]}}
                    @endrole
                </td>
                <td class="align-top">
                    @role('wali', session('semester_id'))
                    <input type="text" class="form-control" wire:model.defer="keterangan_prakerin.{{$siswa->anggota_rombel->anggota_rombel_id}}">
                    @if($errors->has('keterangan_prakerin.'.$siswa->anggota_rombel->anggota_rombel_id))
                        <span class="text-danger">{{ $errors->first('keterangan_prakerin.'.$siswa->anggota_rombel->anggota_rombel_id) }}</span>
                    @endif
                    @else
                    {{$keterangan_prakerin[$siswa->anggota_rombel->anggota_rombel_id]}}
                    @endrole
                </td>
                <td class="align-top text-center">
                    @role('wali', session('semester_id'))
                        @isset($prakerin_id[$siswa->anggota_rombel->anggota_rombel_id])
                        <button type="button" class="btn btn-sm btn-danger waves-effect waves-float waves-light" wire:click="hapusPrakerin('{{$prakerin_id[$siswa->anggota_rombel->anggota_rombel_id]}}', '{{$siswa->anggota_rombel->anggota_rombel_id}}')">Hapus</button>
                        @else
                        -
                        @endif
                    @else
                    -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center" colspan="6">Tidak ada data untuk ditampilkan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
