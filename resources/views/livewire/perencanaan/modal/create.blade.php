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
                    @if($kompetensi_id == 2)
                    <div class="row mb-2">
                        <label for="metode_id" class="col-sm-3 col-form-label">Teknik Penilaian</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="metode_id" class="form-select" wire:model="metode_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Teknik Penilaian ==">
                                <option value="">== Pilih Teknik Penilaian ==</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="bobot" class="col-sm-3 col-form-label">Bobot</label>
                        <div class="col-sm-9">
                            <input type="text" id="bobot" wire:model="bobot" class="form-control" {{$readonly}}>
                        </div>
                    </div>
                    @endif
                    <div class="row mb-2 {{($show) ? '' : 'd-none'}}">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="clone">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="min-width:110px">Aktifitas Penilaian</th>
                                        @if($kompetensi_id == 1)
                                        <th class="text-center" style="min-width:110px;">Teknik</th>
                                        <th class="text-center" width="10">Bobot</th>
                                        @endif
                                        @foreach ($data_kd as $kd)
                                        <?php
                                        $kompetensi_dasar = ($kd->kompetensi_dasar_alias) ? $kd->kompetensi_dasar_alias : $kd->kompetensi_dasar;    
                                        ?>
                                        <th class="text-center"><a href="javascript:void(0)" class="tooltip-top" title="<?php echo strip_tags($kompetensi_dasar); ?>"><?php echo $kd->id_kompetensi; ?></a></th>    
                                        @endforeach
                                        <th class="text-center">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 1; $i <= 5; $i++) {?>
                                    <tr>
                                        <td>
                                            <input class="form-control input-sm" type="text" wire:model="nama_penilaian.{{$i}}" value="" placeholder="<?php echo $placeholder; ?>">
                                        </td>
                                        @if($kompetensi_id == 1)
                                        <td>
                                            <select id="pilih_bobot" class="form-control input-sm" wire:model="bentuk_penilaian.{{$i}}">
                                                <option value="">- Pilih -</option>
                                                <?php 
                                                if($data_bentuk_penilaian){
                                                    foreach($data_bentuk_penilaian as $value){ ?>
                                                <option value="<?php echo $value->teknik_penilaian_id; ?>" data-bobot="<?php echo $value->bobot; ?>"><?php echo $value->nama; ?></option>
                                                <?php } 
                                                } else {
                                                ?>
                                                <option value="">Belum ada</option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input class="form-control input-sm bobot" type="text" wire:model="bobot_penilaian.{{$i}}">
                                        </td>
                                        @endif
                                        <?php
                                        foreach($data_kd as $kd){
                                        ?>
                                        <td style="vertical-align:middle;">
                                            <div class="text-center"><input type="checkbox" wire:model="kd_select.{{$i}}.{{$kd->kompetensi_dasar_id}}" value="{{$kd->kompetensi_dasar_id}}|{{$kd->id_kompetensi}}" /></div>
                                        </td>
                                        <?php } ?>
                                        <td><input class="form-control input-sm" type="text" wire:model="keterangan_penilaian.{{$i}}"></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($data_kd && !$data_kd->count())
                    <div class="row mb-2">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <div class="alert alert-danger" role="alert">
                                <div class="alert-body">
                                    Referensi KD/CP Belum tersedia. Silahkan tambah <a href="/referensi/kompetensi-dasar/tambah" target="_blank">disini</a>
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