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
    <div class="row mb-2">
        <label for="jenis_rombel" class="col-sm-3 col-form-label">Jenis Rombongan Belajar</label>
        <div class="col-sm-9" wire:ignore>
            <select id="jenis_rombel" class="form-select" wire:model="jenis_rombel" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Jenis Rombongan Belajar ==" data-search-off="true" wire:change="changeTingkat">
                <option value="">== Pilih Jenis Rombongan Belajar ==</option>
                <option value="1">Reguler</option>
                <option value="16">Matpel Pilihan</option>
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
</div>
@push('scripts')
<script>
    window.addEventListener('tingkat', event => {
        $('#jenis_rombel').val('')
        $('#jenis_rombel').trigger('change')
        $('#rombongan_belajar_id').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $('#mata_pelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
    });
    window.addEventListener('data_rombongan_belajar', event => {
        $('#rombongan_belajar_id').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $('#mata_pelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_pembelajaran', event => {
        $('#mata_pelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $.each(event.detail.data_pembelajaran, function (i, item) {
            $('#mata_pelajaran_id').append($('<option>', { 
                value: item.mata_pelajaran_id,
                text : item.nama_mata_pelajaran
            }));
        });
    })
</script>
@endpush
