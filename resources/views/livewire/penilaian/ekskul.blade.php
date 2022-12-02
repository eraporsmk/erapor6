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
                        <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Ekstrakurikuler</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Nama Ekstrakurikuler ==" data-search-off="true">
                                <option value="">== Pilih Nama Ekstrakurikuler ==</option>
                                @foreach ($collection as $item)
                                <option value="{{$item->rombongan_belajar_id}}">{{$item->nama_ekskul}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2{{($show) ? '' : ' d-none'}}">
                        <label for="rombongan_belajar_id_reguler" class="col-sm-3 col-form-label">Filter Kelas</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="rombongan_belajar_id_reguler" class="form-select" wire:model="rombongan_belajar_id_reguler" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Semua Kelas ==" data-clear="true">
                                <option value="">== Semua Kelas ==</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2{{($show) ? '' : ' d-none'}}">
                        <label for="reset_nilai" class="col-sm-3 col-form-label">Reset Nilai Ekstrakurikuler</label>
                        <div class="col-sm-9">
                            <a class="btn btn-danger" wire:click="resetNilai">Reset Nilai {{$nama_ekskul}} {{($nama_rombel) ? 'Kelas '.$nama_rombel : ''}}</a>
                        </div>
                    </div>
                    <div class="table-responsive{{($show) ? '' : ' d-none'}}">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="20%">Nama Siswa</th>
                                    <th class="text-center" width="10%">Kelas</th>
                                    <th class="text-center" width="20%">Predikat</th>
                                    <th class="text-center" width="50%">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_siswa as $siswa)
                                <tr>
                                    <td>{{$siswa->nama}}</td>
                                    <td>
                                        {{($siswa->kelas) ? $siswa->kelas->nama : '-'}}
                                    </td>
                                    <td>
                                        <select wire:model.lazy="nilai_ekskul.{{$siswa->anggota_ekskul->anggota_rombel_id}}" class="form-select" wire:change="changeNilai('{{$siswa->anggota_ekskul->anggota_rombel_id}}')">
                                            <option value="">== Pilih Predikat ==</option>
                                            <option value="1">Sangat Baik</option>
                                            <option value="2">Baik</option>
                                            <option value="3">Cukup</option>
                                            <option value="4">Kurang</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="deskripsi_ekskul" wire:model.defer="deskripsi_ekskul.{{$siswa->anggota_ekskul->anggota_rombel_id}}">
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
@push('scripts')
<script>
    window.addEventListener('reset', event => {
        $('#rombongan_belajar_id').val('');
        $('#rombongan_belajar_id').trigger('change');
    });
    window.addEventListener('data_rombongan_belajar', event => {
        $('#rombongan_belajar_id_reguler').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id_reguler').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
</script>

@endpush
