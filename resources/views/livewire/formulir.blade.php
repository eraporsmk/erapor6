<div>
    <div class="row mb-2">
        <label for="semester_id" class="col-sm-3 col-form-label">Tahun Pelajaran</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" readonly wire:model="semester_id">
        </div>
    </div>
    <div class="row mb-2">
        <label for="tingkat" class="col-sm-3 col-form-label">Tingkat Kelas</label>
        <div class="col-sm-9" wire:ignore>
            <select id="tingkat" class="form-select" wire:model="tingkat" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Tingkat Kelas ==" data-search-off="true" wire:change="changeTingkat">
                <option value="">== Pilih Tingkat Kelas ==</option>
                <option value="10">Kelas 10</option>
                <option value="11">Kelas 11</option>
                <option value="12">Kelas 12</option>
                <option value="13">Kelas 13</option>
            </select>
        </div>
    </div>
    <div class="row mb-2" wire:ignore>
        <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Rombongan Belajar</label>
        <div class="col-sm-9">
            <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Rombongan Belajar ==" wire:change="changeRombel">
                <option value="">== Pilih Rombongan Belajar ==</option>
            </select>
        </div>
    </div>
    <div class="row mb-2" wire:ignore>
        <label for="mata_pelajaran_id" class="col-sm-3 col-form-label">Mata Pelajaran</label>
        <div class="col-sm-9">
            <select id="mata_pelajaran_id" class="form-select" wire:model="mata_pelajaran_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Mata Pelajaran ==" wire:change="changePembelajaran">
                <option value="">== Pilih Mata Pelajaran ==</option>
            </select>
        </div>
    </div>
    <div class="row mb-2" wire:ignore>
        <label for="rencana_penilaian_id" class="col-sm-3 col-form-label">Rencana Penilaian</label>
        <div class="col-sm-9" wire:ignore>
            <select id="rencana_penilaian_id" class="form-select" wire:model="rencana_penilaian_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Rencana Penilaian ==" wire:change="changeRencana">
                <option value="">== Pilih Rencana Penilaian ==</option>
            </select>
        </div>
    </div>
    <div class="row mb-2 {{($show) ? '' : 'd-none'}}">
        <div class="col-6">
            <input class="form-control" type="file" wire:model="template_excel">
            @error('template_excel') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="col-6 d-grid">
            <a target="_blank" class="btn btn-primary" href="{{route('unduhan.template-nilai-kd', ['rencana_penilaian_id' => $rencana_penilaian_id])}}">Unduh Template Nilai KD</a>
        </div>
    </div>
    <div class="row mb-2 {{($show) ? '' : 'd-none'}}" wire:loading.remove>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle" rowspan="2">Nama Peserta Didik</th>
                        <th class="text-center" colspan="{{($kd_nilai && count($kd_nilai)) ? count($kd_nilai) : 1}}">KD/CP</th>
                        <th class="text-center align-middle" rowspan="2">Rerata Nilai</th>
                    </tr>
                    <tr>
                        @foreach ($kd_nilai as $kd)
                        @isset($kd->id_kompetensi)
                        <th class="text-center">{{($kd) ? $kd->id_kompetensi : '-'}}</th>
                        @endisset
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_siswa as $siswa)
                    <tr>
                        <td>{{$siswa->nama}}</td>
                        @foreach ($kd_nilai as $kd)
                        <td class="text-center">
                            <div wire:ignore.self>
                                @isset($kd->kd_nilai_id)
                                <input type="number" class="form-control" wire:model.lazy="nilai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$kd->kd_nilai_id}}" wire:change="hitungRerata('{{$siswa->anggota_rombel->anggota_rombel_id}}')">
                                @endisset
                            </div>
                        </td>
                        @endforeach
                        <td class="text-center">
                            <div wire:ignore.self>
                                <input type="text" class="form-control" wire:model.lazy="rerata.{{$siswa->anggota_rombel->anggota_rombel_id}}" readonly>
                            </div>
                        </td>
                    </tr>
                    @endforeach      
                </tbody>
            </table>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('data_rombongan_belajar', event => {
        $('#rombongan_belajar_id').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $('#mata_pelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $('#rencana_penilaian_id').html('<option value="">== Pilih Rencana Penilaian ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_pembelajaran', event => {
        $('#mata_pelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $('#rencana_penilaian_id').html('<option value="">== Pilih Rencana Penilaian ==</option>')
        $.each(event.detail.data_pembelajaran, function (i, item) {
            $('#mata_pelajaran_id').append($('<option>', { 
                value: item.mata_pelajaran_id,
                text : item.nama_mata_pelajaran
            }));
        });
    })
    window.addEventListener('data_rencana', event => {
        console.log(event.detail);
        $('#rencana_penilaian_id').html('<option value="">== Pilih Rencana Penilaian ==</option>')
        $.each(event.detail.data_rencana, function (i, item) {
            $('#rencana_penilaian_id').append($('<option>', { 
                value: item.rencana_penilaian_id,
                text : item.nama_penilaian
            }));
        });
    })
</script>
@endpush
