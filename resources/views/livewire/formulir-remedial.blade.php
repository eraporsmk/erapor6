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
        <label for="pembelajaran_id" class="col-sm-3 col-form-label">Mata Pelajaran</label>
        <div class="col-sm-9">
            <select id="pembelajaran_id" class="form-select" wire:model="pembelajaran_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Mata Pelajaran ==">
                <option value="">== Pilih Mata Pelajaran ==</option>
            </select>
        </div>
    </div>
    <div class="row mb-2" wire:ignore>
        <label for="kompetensi_id" class="col-sm-3 col-form-label">Kompetensi Penilaian</label>
        <div class="col-sm-9">
            <select id="kompetensi_id" class="form-select" wire:model="kompetensi_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Kompetensi Penilaian ==">
                <option value="">== Pilih Kompetensi Penilaian ==</option>
            </select>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('data_rombongan_belajar', event => {
        $('#rombongan_belajar_id').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $('#pembelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $('#rencana_penilaian_id').html('<option value="">== Pilih Rencana Penilaian ==</option>')
        $('#kompetensi_id').html('<option value="">== Pilih Kompetensi Penilaian ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_pembelajaran', event => {
        $('#pembelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $('#rencana_penilaian_id').html('<option value="">== Pilih Rencana Penilaian ==</option>')
        $('#kompetensi_id').html('<option value="">== Pilih Kompetensi Penilaian ==</option>')
        $.each(event.detail.data_pembelajaran, function (i, item) {
            $('#pembelajaran_id').append($('<option>', { 
                value: item.pembelajaran_id,
                text : item.nama_mata_pelajaran
            }));
        });
    })
    window.addEventListener('data_kompetensi', event => {
        console.log(event.detail.data_kompetensi);
        $('#kompetensi_id').html('<option value="">== Pilih Kompetensi Penilaian ==</option>')
        $('#rencana_penilaian_id').html('<option value="">== Pilih Rencana Penilaian ==</option>')
        $.each(event.detail.data_kompetensi, function (i, item) {
            $('#kompetensi_id').append($('<option>', { 
                value: item.id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_rencana', event => {
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
