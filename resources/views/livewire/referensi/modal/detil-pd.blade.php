<div>
    <div wire:ignore.self class="modal fade" id="detilPD" tabindex="-1" aria-labelledby="detilPDLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detilPDLabel">Detil {{$data}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="nama">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nis" class="col-sm-3 col-form-label">NIS</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="nis">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nisn" class="col-sm-3 col-form-label">NISN</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="nisn">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nik" class="col-sm-3 col-form-label">NIK</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="nik">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="jenis_kelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="jenis_kelamin">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="tempat_tanggal_lahir" class="col-sm-3 col-form-label">Tempat, Tanggal Lahir</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="tempat_tanggal_lahir">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="agama" class="col-sm-3 col-form-label">Agama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="agama">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="status" class="col-sm-3 col-form-label">Status dalam keluarga</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="status" class="form-select" wire:model="status" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-search-off="true" data-parent="#detilPD" data-placeholder="== Pilih Status ==">
                                <option value="Anak Kandung">Anak Kandung</option>
                                <option value="Anak Tiri">Anak Tiri</option>
                                <option value="Anak Angkat">Anak Angkat</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="anak_ke" class="col-sm-3 col-form-label">Anak Ke</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" wire:model="anak_ke">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="alamat">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="rt" class="col-sm-3 col-form-label">RT/RW</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="rt_rw">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="desa_kelurahan" class="col-sm-3 col-form-label">Desa/Kelurahan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="desa_kelurahan">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kecamatan" class="col-sm-3 col-form-label">Kecamatan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="kecamatan">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kode_pos" class="col-sm-3 col-form-label">Kodepos</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="kode_pos">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="no_hp" class="col-sm-3 col-form-label">Telp/HP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="no_hp">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="sekolah_asal" class="col-sm-3 col-form-label">Asal Sekolah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="sekolah_asal">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="diterima_kelas" class="col-sm-3 col-form-label">Diterima dikelas</label>
                        <div class="col-sm-9">
                            <input wire:model="diterima_kelas" type="text" class="form-control" placeholder="" aria-label="Diterima dikelas" aria-describedby="button-addon1">
                            <!--div class="input-group">
                                <button wire:loading wire:target="syncPD" class="btn btn-outline-primary" type="button" id="button-addon1">
                                    <div class="spinner-border spinner-border-sm text-info" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>                                      
                                </button>
                                <button wire:loading.remove wire:target="syncPD" class="btn btn-outline-primary" type="button" id="button-addon1" wire:click="syncPD"><i class="fa-solid fa-rotate"></i></button>
                                <input wire:model="diterima_kelas" type="text" class="form-control" placeholder="" aria-label="Diterima dikelas" aria-describedby="button-addon1">
                            </div-->                              
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="diterima" class="col-sm-3 col-form-label">Diterima pada tanggal</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="diterima">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.lazy="email">
                            @error('email') <span class="text-danger">{{$message}}</span> @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nama_ayah" class="col-sm-3 col-form-label">Nama Ayah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="nama_ayah">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kerja_ayah" class="col-sm-3 col-form-label">Pekerjaan Ayah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="kerja_ayah">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nama_ibu" class="col-sm-3 col-form-label">Nama Ibu Kandung</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="nama_ibu">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kerja_ibu" class="col-sm-3 col-form-label">Pekerjaan Ibu Kandung</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="kerja_ibu">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nama_wali" class="col-sm-3 col-form-label">Nama Wali</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" wire:model="nama_wali">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="alamat_wali" class="col-sm-3 col-form-label">Alamat Wali</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" wire:model="alamat_wali">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="telp_wali" class="col-sm-3 col-form-label">Telp/HP Wali</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" wire:model="telp_wali">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kerja_wali" class="col-sm-3 col-form-label">Pekerjaan Wali</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="kerja_wali" class="form-select" wire:model="kerja_wali" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-search-off="true" data-parent="#detilPD" data-placeholder="== Pilih Pekerjaan Wali ==">
                                <option value="">== Pilih Pekerjaan Wali ==</option>
                                @foreach($pekerjaan_wali as $wali)
                                <option value="{{$wali->pekerjaan_id}}">{{$wali->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:loading.remove wire:target="perbaharui" type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click.prevent="tutup">Tutup</button>
                    <button wire:loading wire:target="perbaharui" class="btn btn-outline-primary" type="button" id="button-addon1">
                        <div class="spinner-border spinner-border-sm text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>                                      
                    </button>
                    <button wire:loading.remove wire:target="perbaharui" type="submit" class="btn btn-primary" wire:click.prevent="perbaharui">Perbaharui</button>
                </div>
            </div>
        </div>
    </div>
</div>
