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
                                @foreach ($data_rombongan_belajar as $rombongan_belajar)
                                <option value="{{$rombongan_belajar->rombongan_belajar_id}}">{{$rombongan_belajar->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="pembelajaran_id" class="col-sm-3 col-form-label">Mata Pelajaran</label>
                        <div class="col-sm-9">
                            <select id="pembelajaran_id" class="form-select" wire:model="pembelajaran_id" wire:change="changePembelajaran">
                                <option value="">== Pilih Mata Pelajaran ==</option>
                                @foreach ($data_pembelajaran as $pembelajaran)
                                <option value="{{$pembelajaran->pembelajaran_id}}">{{$pembelajaran->nama_mata_pelajaran}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2{{($show_reset) ? '' : ' d-none'}}">
                        <label class="col-sm-3 col-form-label">Reset Capaian Kompetensi</label>
                        <div class="col-sm-9">
                            <button type="button" class="btn btn-danger" wire:click="resetData">Reset Capaian Kompetensi</button>
                        </div>
                    </div>
                    <div class="table-responsive{{($show) ? '' : ' d-none'}}">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2">Nama Peserta Didik</th>
                                    <th class="text-center" rowspan="2">Nilai Akhir</th>
                                    <th colspan="2" class="text-center">Capaian Kompetensi</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Kompetensi yang sudah dicapai</th>
                                    <th class="text-center">Kompetensi yang perlu ditingkatkan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_siswa as $siswa)
                                    <tr>
                                        <td class="align-middle">{{$siswa->nama}}</td>
                                        <td class="align-middle text-center">
                                            <div wire:ignore>
                                                {{($siswa->anggota_rombel->nilai_akhir_mapel) ? $siswa->anggota_rombel->nilai_akhir_mapel->nilai : 0}}
                                            </div>
                                        </td>
                                        <td>
                                            <textarea wire:ignore wire:model="deskripsi_kompeten.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="textarea form-control" rows="5"></textarea>
                                        </td>
                                        <td>
                                            <textarea wire:ignore wire:model="deskripsi_inkompeten.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="textarea form-control" rows="5"></textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer{{($show) ? '' : ' d-none'}}">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @include('components.loader')
</div>
