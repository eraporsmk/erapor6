<div>
    @include('panels.breadcrumb')
    <div class="content-body">    
        <div class="card">
            <form wire:submit.prevent="store">
            <div class="card-body">
                <div class="row">
                    <div class="col-7">
                        <div class="mb-1">
                            <label for="semester_id" class="form-label">Periode Aktif</label>
                            <div wire:ignore>
                                <select wire:model="semester_id" class="form-select" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Semester Aktif ==" data-search-off="true">
                                    <option value="">== Pilih Semester Aktif ==</option>
                                    @foreach ($data_semester as $semester)
                                    <option value="{{$semester->semester_id}}">{{$semester->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('semester_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 d-none">
                            <label for="cara_penilaian" class="form-label">Opsi Penilaian</label>
                            <div wire:ignore>
                                <select wire:model="cara_penilaian" class="form-select" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Opsi Penilaian ==" data-search-off="true">
                                    <option value="">== Pilih Opsi Penilaian ==</option>
                                    <option value="sederhana">Sederhana</option>
                                    <option value="lengkap">Lengkap</option>
                                </select>
                            </div>
                            @error('cara_penilaian')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1">
                            <label for="tanggal_rapor" class="form-label">Tanggal Rapor UTS </label>
                            <x-date-picker wire:model.lazy="tanggal_rapor_uts" class="form-control"/>
                            @error('tanggal_rapor_uts')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1">
                            <label for="tanggal_rapor" class="form-label">Tanggal Rapor Semester</label>
                            <x-date-picker wire:model.lazy="tanggal_rapor" class="form-control"/>
                            @error('tanggal_rapor')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1">
                            <label for="zona" class="form-label">Zona Waktu</label>
                            <div wire:ignore>
                                <select wire:model="zona" class="form-select" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Zona Waktu ==" data-search-off="true">
                                    <option value="">== Pilih Zona Waktu ==</option>
                                    <option value="Asia/Jakarta" selected>Waktu Indonesia Barat (WIB)</option>
                                    <option value="Asia/Makassar">Waktu Indonesia Tengah (WITA)</option>
                                    <option value="Asia/Jayapura">Waktu Indonesia Timur (WIT)</option>
                                </select>
                            </div>
                            @error('zona')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1">
                            <label for="kepala_sekolah" class="form-label">Kepala Sekolah</label>
                            <div wire:ignore>
                                <select wire:model="kepala_sekolah" class="form-select" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Kepala Sekolah ==">
                                    <option value="">== Pilih Kepala Sekolah ==</option>
                                    @foreach ($data_guru as $guru)
                                    <option value="{{$guru->guru_id}}">{{$guru->nama_lengkap}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('kepala_sekolah')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1">
                            <label for="rombel_4_tahun" class="form-label">Rombongan Belajar 4 Tahun</label>
                            <div wire:ignore>
                                <select wire:model="rombel_4_tahun" class="form-select" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Rombongan Belajar 4 Tahun ==" multiple="multiple" data-tags="true">
                                    @foreach ($data_rombel as $rombel)
                                    <option value="{{$rombel->rombongan_belajar_id}}">{{$rombel->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="mb-1 text-center">
                            <label for="logo" class="form-label">Logo Sekolah 
                                @if($sekolah->logo_sekolah)
                                <a wire:click="resetLogo" class="btn btn-sm btn-outline text-danger" title="Reset Logo"><i class="fas fa-trash"></i></a>
                                @endif
                            </label>
                            <div class="mb-1">
                                <img src="{{($sekolah->logo_sekolah) ? asset('storage/images/'.$sekolah->logo_sekolah) : asset('images/tutwuri.png')}}" class="img-thumbnail" alt="Logo Sekolah">
                            </div>
                            <input type="file" class="form-control" wire:model="photo">
                            @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <!--button type="submit" class="btn btn-primary">Simpan</button-->
                <div class="spinner-border text-primary" role="status" wire:loading wire:target="photo">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <button type="submit" class="btn btn-primary" wire:target="photo" wire:loading.remove>Simpan</button>
            </div>
            </form>
        </div>
    </div>
    @include('components.loader')
</div>