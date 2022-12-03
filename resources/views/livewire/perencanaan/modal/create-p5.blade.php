<div>
    <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
                            @error('tingkat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2" wire:loading.remove>
                        <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Rombongan Belajar</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Rombongan Belajar ==" wire:change="changeRombel">
                                <option value="">== Pilih Rombongan Belajar ==</option>
                            </select>
                            @error('rombongan_belajar_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2" wire:loading.remove>
                        <label for="pembelajaran_id" class="col-sm-3 col-form-label">Tema</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="pembelajaran_id" class="form-select" wire:model="pembelajaran_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Tema ==">
                                <option value="">== Pilih Tema ==</option>
                            </select>
                            @error('pembelajaran_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nama_projek" class="col-sm-3 col-form-label">Nama Projek</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" id="nama_projek" wire:model.defer="nama_projek">
                            @error('nama_projek')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi Projek</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="deskripsi" wire:model.defer="deskripsi"></textarea>
                            @error('deskripsi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2 {{($show) ? '' : 'd-none'}}">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Dimensi</th>
                                        <th class="text-center">Elemen</th>
                                        <th class="text-center">Sub Elemen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($budaya_kerja as $kerja)
                                    @foreach($kerja->elemen_budaya_kerja as $elemen)
                                    <tr class="table-{{warna_dimensi($kerja->budaya_kerja_id)}}">
                                        <td>
                                            <div class="text-center"><input type="checkbox" wire:model.defer="sub_elemen.{{$elemen->elemen_id}}" value="{{$kerja->budaya_kerja_id}}|{{$elemen->elemen_id}}" /></div>
                                        </td>
                                        <td>{{$kerja->aspek}}</td>
                                        <td>{{$elemen->elemen}}</td>
                                        <td>{{$elemen->deskripsi}}</td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary {{($show) ? '' : 'd-none'}}" wire:click.prevent="store()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>