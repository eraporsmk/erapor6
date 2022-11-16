<div>
    <div wire:ignore.self class="modal fade" id="detilGuru" tabindex="-1" aria-labelledby="detilGuruLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detilGuruLabel">Detil {{$data}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="nama">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="gelar_depan" class="col-sm-3 col-form-label">Gelar Depan</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="gelar_depan" class="form-select" wire:model="gelar_depan" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-search-off="true" data-tags="true" multiple="true" data-parent="#detilGuru" data-placeholder="== Pilih Gelar Depan ==">
                                <option value="">== Pilih Gelar Depan ==</option>
                            </select>
                            {{--
                            <select id="gelar_depan" class="form-select" wire:model="gelar_depan" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Gelar Depan ==" data-parent="#detilGuru">
                                <option value="">== Pilih Gelar Depan ==</option>
                                @foreach ($ref_gelar_depan as $depan)
                                    <option value="{{$depan->gelar_akademik_id}}">{{$depan->kode}}</option>
                                @endforeach
                            </select>
                            --}}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="gelar_belakang" class="col-sm-3 col-form-label">Gelar Belakang</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="gelar_belakang" class="form-select" wire:model="gelar_belakang" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-search-off="true" data-tags="true" multiple="true" data-parent="#detilGuru" data-placeholder="== Pilih Gelar Belakang ==">
                                <option value="">== Pilih Gelar Belakang ==</option>
                            </select>
                            {{--
                            <select id="gelar_belakang" class="form-select" wire:model="gelar_belakang" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Gelar Belakang ==" data-parent="#detilGuru">
                                <option value="">== Pilih Gelar Belakang ==</option>
                                @foreach ($ref_gelar_belakang as $belakang)
                                    <option value="{{$belakang->gelar_akademik_id}}">{{$belakang->kode}}</option>
                                @endforeach
                            </select>
                            --}}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nuptk" class="col-sm-3 col-form-label">NUPTK</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="nuptk">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nip" class="col-sm-3 col-form-label">NIP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="nip">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nik" class="col-sm-3 col-form-label">NIK</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="nik">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="jenis_kelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-9">
                            <select id="jenis_kelamin" class="form-select" wire:model="jenis_kelamin" {{$disabled}}>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="tempat_lahir" class="col-sm-3 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="tempat_lahir">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="tanggal_lahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-9">
                            @if($readonly)
                            <input type="text" class="form-control" {{$readonly}} wire:model="tanggal_lahir">
                            @else
                            <input type="text" class="form-control" wire:model="tanggal_lahir">
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="agama_id" class="col-sm-3 col-form-label">Agama</label>
                        <div class="col-sm-9">
                            <select id="agama_id" class="form-select" wire:model="agama_id" {{$disabled}}>
                                @foreach ($ref_agama as $agama)
                                <option value="{{$agama->agama_id}}">{{$agama->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="alamat">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="rt" class="col-sm-3 col-form-label">RT</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="rt">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="rw" class="col-sm-3 col-form-label">RW</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="rw">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="desa_kelurahan" class="col-sm-3 col-form-label">Desa/Kelurahan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="desa_kelurahan">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kecamatan" class="col-sm-3 col-form-label">Kecamatan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="kecamatan">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kode_pos" class="col-sm-3 col-form-label">Kodepos</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="kode_pos">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="no_hp" class="col-sm-3 col-form-label">Telp/HP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" {{$readonly}} wire:model="no_hp">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" {{$readonly}} wire:model="email">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="jenis_ptk_id" class="col-sm-3 col-form-label">Jenis PTK</label>
                        <div class="col-sm-9">
                            <select id="agama_id" class="form-select" wire:model="jenis_ptk_id" {{$disabled}}>
                                @foreach ($ref_jenis_ptk as $jenis_ptk)
                                <option value="{{$jenis_ptk->jenis_ptk_id}}">{{$jenis_ptk->jenis_ptk}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="status_kepegawaian_id" class="col-sm-3 col-form-label">Status Kepegawaian</label>
                        <div class="col-sm-9">
                            <select id="agama_id" class="form-select" wire:model="status_kepegawaian_id" {{$disabled}}>
                                @foreach ($ref_status_kepegawaian as $status_kepegawaian)
                                <option value="{{$status_kepegawaian->status_kepegawaian_id}}">{{$status_kepegawaian->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if($opsi_dudi)
                    <div class="row mb-2">
                        <label for="dudi_id" class="col-sm-3 col-form-label">DUDI</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="dudi_id" class="form-select" wire:model="dudi_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-search-off="true" data-parent="#detilGuru" data-placeholder="== Pilih DUDI ==">
                                <option value="">== Pilih DUDI ==</option>
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    @if($hapus)
                    <button type="submit" class="btn btn-danger" wire:click.prevent="hapus()">Hapus</button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="perbaharui()">Perbaharui</button>
                </div>
            </div>
        </div>
    </div>
</div>
