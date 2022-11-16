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
    {{--
    <div class="row mb-2" wire:ignore>
        <label for="pembelajaran_id" class="col-sm-3 col-form-label">Tema</label>
        <div class="col-sm-9">
            <select id="pembelajaran_id" class="form-select" wire:model="pembelajaran_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Mata Pelajaran ==" wire:change="changePembelajaran">
                <option value="">== Pilih Tema ==</option>
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
    --}}
    <div class="row mb-2 {{($show && $jumlah_elemen) ? '' : 'd-none'}}">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle" rowspan="3">Nama Peserta Didik</th>
                        <th class="text-center" colspan="{{$jumlah_elemen}}">Sub Elemen</th>
                    </tr>
                    <tr>
                        @foreach ($rencana_budaya_kerja as $rencana)
                        <th class="text-center" colspan="{{$rencana->aspek_budaya_kerja->count()}}">{{$rencana->nama}} ({{$rencana->pembelajaran->nama_mata_pelajaran}})</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($rencana_budaya_kerja as $rencana)
                        @foreach ($rencana->aspek_budaya_kerja as $aspek)
                        <th class="text-center" style="font-style: normal;">
                            <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="<p><strong>Dimensi:</strong> {{$aspek->budaya_kerja->aspek}}</p>
                                <p><strong>Elemen:</strong> {{$aspek->elemen_budaya_kerja->elemen}}</p>
                                <p><strong>Sub Elemen:</strong> {{$aspek->elemen_budaya_kerja->deskripsi}}</p>" data-bs-placement="left" >
                                {{$loop->iteration}}
                            </a>
                        </th>    
                        @endforeach   
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_siswa as $siswa)
                    <tr>
                        <td rowspan="2">{{$siswa->nama}}</td>
                        @foreach ($rencana_budaya_kerja as $rencana)
                        @foreach ($rencana->aspek_budaya_kerja as $aspek)
                        <td class="text-center">
                            <select id="nilai" class="form-select" wire:ignore wire:model="nilai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$aspek->aspek_budaya_kerja_id}}">
                                <option value="">-</option>
                                @foreach ($opsi_budaya_kerja as $opsi)
                                <option value="{{$opsi->opsi_id}}|{{($aspek->elemen_id) ? $aspek->elemen_id : 0}}">{{$opsi->nama}}</option>
                                @endforeach
                            </select>
                        </td>
                        @endforeach
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan="{{$jumlah_elemen + 1}}">
                            <textarea title="Catatan proses" placeholder="Catatan proses" wire:ignore wire:model="deskripsi.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="form-control"></textarea>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($show && !$jumlah_elemen)
        <div class="alert alert-danger alert-block">
            <div class="alert-body">
                <p><i class="fas fa-ban"></i> <strong>Informasi!</strong></p>
                <p>Perencanaan P5 di Rombongan Belajar terpilih belum ada!</p>
            </div>
        </div>
    @endif
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('data_rombongan_belajar', event => {
        $('#rombongan_belajar_id').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $('#pembelajaran_id').html('<option value="">== Pilih Tema ==</option>')
        $('#rencana_penilaian_id').html('<option value="">== Pilih Projek ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('tooltip', event => {
        $('[data-bs-toggle="tooltip"]').tooltip()
    });
    window.addEventListener('data_pembelajaran', event => {
        $('#pembelajaran_id').html('<option value="">== Pilih Tema ==</option>')
        $('#rencana_penilaian_id').html('<option value="">== Pilih Projek ==</option>')
        $.each(event.detail.data_pembelajaran, function (i, item) {
            $('#pembelajaran_id').append($('<option>', { 
                value: item.pembelajaran_id,
                text : item.nama_mata_pelajaran
            }));
        });
    })
    window.addEventListener('data_rencana', event => {
        console.log(event.detail);
        $('#rencana_penilaian_id').html('<option value="">== Pilih Projek ==</option>')
        $.each(event.detail.data_rencana, function (i, item) {
            $('#rencana_penilaian_id').append($('<option>', { 
                value: item.rencana_budaya_kerja_id,
                text : item.nama
            }));
        });
    })
</script>
@endpush
