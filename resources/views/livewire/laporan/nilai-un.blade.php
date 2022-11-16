<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        @if(count($data_pembelajaran))
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
                        <label for="jurusan_sp_id" class="col-sm-3 col-form-label">Kompetensi Keahlian</label>
                        <div class="col-sm-9">
                            <select id="jurusan_sp_id" class="form-select" wire:model="jurusan_sp_id" wire:change="changeJurusan">
                                <option value="">== Pilih Kompetensi Keahlian ==</option>
                                @foreach ($jurusan_sp as $jurusan)
                                <option value="{{$jurusan->jurusan_sp_id}}">{{$jurusan->nama_jurusan_sp}}</option>
                                @endforeach
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
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary {{($show) ? '' : 'd-none'}}">Simpan</button>
                </div>
            </form>
        </div>
        @else
        <div class="card">
            <div class="card-body">
                <div class="alert alert-danger alert-block">
                    <div class="alert-body">
                        <p><i class="fas fa-ban"></i><strong>Akses Ditutup!</strong></p>
                        <p>Anda tidak menjabat wali kelas tingkat akhir</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
