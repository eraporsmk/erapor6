<div>
    <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Perencanaan {{$nama}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="semester_id" class="col-sm-3 col-form-label">Tahun Pelajaran</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="semester_id">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="tingkat" class="col-sm-3 col-form-label">Tingkat Kelas</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="tingkat" class="form-select" wire:model="tingkat" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Tingkat Kelas ==" wire:change="changeTingkat">
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
                        <div class="col-sm-9" wire:ignore>
                            <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Rombongan Belajar ==" wire:change="changeRombel">
                                <option value="">== Pilih Rombongan Belajar ==</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="pembelajaran_id" class="col-sm-3 col-form-label">Mata Pelajaran</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="pembelajaran_id" class="form-select" wire:model="pembelajaran_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Mata Pelajaran ==" wire:change="changePembelajaran">
                                <option value="">== Pilih Mata Pelajaran ==</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="cp_id" class="col-sm-3 col-form-label">Capaian Pembelajaran</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="cp_id" class="form-select" wire:model="cp_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Capaian Pembelajaran ==">
                                <option value="">== Pilih Capaian Pembelajaran ==</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2 {{($show && $jml_tp) ? '' : 'd-none'}}">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="clone">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="min-width:110px" rowspan="2">Aktifitas Penilaian</th>
                                        <th class="text-center" colspan="{{$jml_tp}}">Tujuan Pembelajaran</th>{{----}}
                                        <th class="text-center" rowspan="2">Keterangan</th>
                                    </tr>
                                    <tr>
                                        @if($data_tp)
                                            @foreach($data_tp as $tp)
                                            <th class="text-center"><a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="{{$tp->deskripsi}}">{{$loop->iteration}}</a></th>
                                            @endforeach
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 1; $i <= 5; $i++)
                                    <tr>
                                        <td><input class="form-control input-sm" type="text" wire:model="nama_penilaian.{{$i}}"></td>
                                        @if($data_tp)
                                        @foreach($data_tp as $tp)
                                        <td>
                                            {{--
                                            <select id="tp_id" class="form-control input-sm" wire:model="tp_id.{{$i}}">
                                                <option value="">- Pilih TP -</option>
                                                <?php 
                                                if($data_tp){
                                                    foreach($data_tp as $tp){ ?>
                                                <option value="{{$tp->tp_id}}">{{$tp->deskripsi}}</option>
                                                <?php } 
                                                } else {
                                                ?>
                                                <option value="">Belum ada</option>
                                                <?php } ?>
                                            </select>
                                            --}}
                                            <div class="text-center">
                                                <input type="checkbox" wire:model="tp_id.{{$i}}.{{$tp->tp_id}}" value="{{$tp->kompetensi_dasar_id}}" />
                                            </div>
                                        </td>
                                        @endforeach
                                        @endif
                                        <td><input class="form-control input-sm" type="text" wire:model="keterangan_penilaian.{{$i}}"></td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($show && !$jml_tp)
                    <div class="row mb-2">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <div class="alert alert-danger" role="alert">
                                <div class="alert-body">
                                    Referensi Tujuan Pembelajaran (TP) Belum tersedia. Silahkan tambah <a href="/referensi/tujuan-pembelajaran/tambah" target="_blank">disini</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary {{($show) ? '' : 'd-none'}}" wire:click.prevent="store()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>