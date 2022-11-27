<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:ignore.self wire:submit.prevent="store">
                <div class="card-body">
                    <div class="row mb-2">
                        <label for="semester_id" class="col-sm-3 col-form-label">Tahun Pelajaran</label>
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
                    {{--
                    <div class="row mb-2 {{($show) ? '' : 'd-none'}}" wire:loading.remove wire:target="changePembelajaran">
                        <div class="col-6">
                            <input class="form-control" type="file" wire:model="template_excel">
                            @error('template_excel') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6 d-grid">
                            <a target="_blank" class="btn btn-primary" href="{{route('unduhan.template-nilai-akhir', ['pembelajaran_id' => $pembelajaran_id])}}">Unduh Template Nilai Akhir</a>
                        </div>
                    </div>
                    --}}
                    <div class="row mb-2 {{($show) ? '' : 'd-none'}}" wire:loading.remove wire:target="changePembelajaran">
                        <div class="table-responsive">
                            <table class="table table-bordered {{table_striped()}}">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" rowspan="2">Nama Peserta Didik</th>
                                        <th class="text-center align-middle" rowspan="2">Nilai Akhir</th>
                                        <th class="text-center align-middle" colspan="2">Capaian Kompetensi</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center align-middle">Kompetensi yang sudah dicapai</th>
                                        <th class="text-center align-middle">Kompetensi yang perlu ditingkatkan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_siswa as $siswa)
                                    <tr>
                                        <td>
                                            {{$siswa->nama}}
                                        </td>
                                        <td class="text-center">
                                            <input type="number" wire:ignore class="form-control" wire:model="nilai.{{$siswa->anggota_rombel->anggota_rombel_id}}">
                                        </td>
                                        <td>
                                            @foreach ($data_tp as $tp)
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="{{$tp->tp_id}}" id="tp_dicapai-{{$tp->tp_id}}" wire:model="tp_dicapai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$tp->tp_id}}"
                                                @if(isset($tp_belum_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id]) && $tp_belum_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id])
                                                disabled
                                                @endif
                                                >
                                                <label class="form-check-label" for="tp_dicapai-{{$tp->tp_id}}">
                                                    {{$tp->deskripsi}}
                                                </label>
                                            </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($data_tp as $tp)
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="{{$tp->tp_id}}" id="tp_belum_dicapai-{{$tp->tp_id}}" wire:model="tp_belum_dicapai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$tp->tp_id}}"
                                                @if(isset($tp_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id]) && $tp_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id])
                                                disabled
                                                @endif
                                                >
                                                <label class="form-check-label" for="tp_belum_dicapai-{{$tp->tp_id}}">
                                                    {{$tp->deskripsi}}
                                                </label>
                                            </div>
                                            @endforeach
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
    @include('components.loader')
</div>
