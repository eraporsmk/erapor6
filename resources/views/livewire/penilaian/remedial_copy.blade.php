<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:ignore.self wire:submit.prevent="store">
                <div class="card-body">
                    <div class="row mb-2">
                        <label for="semester_id" class="col-sm-3 col-form-label">Tahun Ajaran</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="semester_id">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="tingkat" class="col-sm-3 col-form-label">Tingkat Kelas</label>
                        <div class="col-sm-9">
                            <select id="tingkat" class="form-select" wire:model="tingkat" wire:change="changeTingkat">
                                <option value="">== Pilih Tingkat Kelas ==</option>
                                <option value="10">Kelas 10</option>
                                <option value="11">Kelas 11</option>
                                <option value="12">Kelas 12</option>
                                <option value="13">Kelas 13</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Rombongan Belajar</label>
                        <div class="col-sm-9">
                            <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" wire:change="changeRombel">
                                <option value="">== Pilih Rombongan Belajar ==</option>
                                @if($data_rombongan_belajar)
                                @foreach ($data_rombongan_belajar as $rombongan_belajar)
                                <option value="{{$rombongan_belajar->rombongan_belajar_id}}">{{$rombongan_belajar->nama}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="mata_pelajaran_id" class="col-sm-3 col-form-label">Mata Pelajaran</label>
                        <div class="col-sm-9">
                            <select id="mata_pelajaran_id" class="form-select" wire:model="mata_pelajaran_id" wire:change="changePembelajaran">
                                <option value="">== Pilih Mata Pelajaran ==</option>
                                @if($data_pembelajaran)
                                @foreach ($data_pembelajaran as $pembelajaran)
                                <option value="{{$pembelajaran->mata_pelajaran_id}}">{{$pembelajaran->nama_mata_pelajaran}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kompetensi_id" class="col-sm-3 col-form-label">Aspek Penilaian</label>
                        <div class="col-sm-9">
                            <select id="kompetensi_id" class="form-select" wire:model="kompetensi_id" wire:change="changeKompetensi">
                                <option value="">== Pilih Rencana Penilaian ==</option>
                                <option value="1">Pengetahuan</option>
                                <option value="2">Keterampilan</option>
                                @if(session('semester_aktif') == 20211 || session('semester_aktif') == 20212)
                                <option value="3">Pusat Keunggulan</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2 {{($show) ? '' : 'd-none'}}" wire:loading.remove wire:target="changeKompetensi">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody><tr>
                                        <td colspan="2" class="text-center"><strong>Keterangan</strong></td>
                                    </tr>
                                    <tr>
                                        <td width="30%">SKM</td>
                                        <td>{{$skm}}</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="bg-danger form-control input-sm"></td>
                                        <td>Tidak tuntas (input aktif)</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="bg-success form-control input-sm"></td>
                                        <td>Tuntas (input non aktif)</td>
                                    </tr>
                                </tbody></table>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" rowspan="2">Nama Peserta Didik</th>
                                        <th class="text-center" colspan="{{($kd_nilai && $kd_nilai->count()) ? $kd_nilai->count() : 1}}">KD/CP</th>
                                        <th class="text-center align-middle" rowspan="2">Rerata Akhir</th>
                                        <th class="text-center align-middle" rowspan="2">Rerata Remedial</th>
                                        <th class="text-center align-middle" rowspan="2">Hapus</th>
                                    </tr>
                                    <tr>
                                        @forelse ($kd_nilai as $kd)
                                        <th class="text-center">
                                            <div wire:ignore>
                                                {{$kd->id_kompetensi}}
                                                <input type="hidden" wire:model="kompetensi_dasar_id.{{$kd->kompetensi_dasar_id}}">
                                            </div>
                                        </th>
                                        @empty
                                        <th class="text-center">
                                            -
                                        </th>
                                        @endforelse
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_siswa as $siswa)
                                    <tr>
                                        <td>
                                            {{$siswa->nama}}
                                        </td>
                                        @forelse ($kd_nilai as $kd)
                                        <td class="text-center">
                                            @isset($nilai[$siswa->anggota_rombel->anggota_rombel_id][$kd->kompetensi_dasar_id])
                                            @if($nilai[$siswa->anggota_rombel->anggota_rombel_id][$kd->kompetensi_dasar_id] < $skm)
                                            <div wire:ignore>
                                                <input type="number" class="bg-danger text-white form-control" wire:model="nilai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$kd->kompetensi_dasar_id}}">
                                            </div>
                                            @else
                                            <div wire:ignore>
                                                <input type="number" class="bg-success text-white form-control" wire:model="nilai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$kd->kompetensi_dasar_id}}" readonly>
                                            </div>
                                            @endif
                                            @endisset
                                        </td>
                                        @empty
                                        <td class="text-center">Tidak ditemukan perencanaan</td>
                                        @endforelse
                                        <td class="text-center">
                                            <div wire:ignore>
                                                <input type="text" class="form-control-plaintext" wire:model="rerata.{{$siswa->anggota_rombel->anggota_rombel_id}}" readonly>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div wire:ignore>
                                                <input type="text" class="form-control-plaintext text-danger" wire:model="remedial.{{$siswa->anggota_rombel->anggota_rombel_id}}" readonly>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($siswa->nilai_remedial)
                                            <a href="" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach      
                                </tbody>
                            </table>
                        </div>
                    </div>                        
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary {{($show) ? '' : 'd-none'}}">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
