<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('livewire.formulir-umum')
                @if($show_tp)
                <div class="row mb-2">
                    <label for="cp_id" class="col-sm-3 col-form-label">Capaian Pembelajaran (CP)</label>
                    <div class="col-sm-9" wire:ignore>
                        <select id="cp_id" class="form-select" wire:model="cp_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Capaian Pembelajaran (CP) ==">
                            <option value="">== Pilih Capaian Pembelajaran (CP) ==</option>
                        </select>
                        @error('cp_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif
                @if($show_kd)
                <div class="row mb-2">
                    <label for="kompetensi_id" class="col-sm-3 col-form-label">Kompetensi</label>
                    <div class="col-sm-9" wire:ignore>
                        <select id="kompetensi_id" class="form-select" wire:model="kompetensi_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Kompetensi ==" data-search-off="true">
                            <option value="">== Pilih Kompetensi ==</option>
                            <option value="1">Pengetahuan</option>
                            <option value="2">Keterampilan</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="kd_id" class="col-sm-3 col-form-label">Kompetensi Dasar (KD)</label>
                    <div class="col-sm-9" wire:ignore>
                        <select id="kd_id" class="form-select" wire:model="kd_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Kompetensi Dasar (KD) ==">
                            <option value="">== Pilih Kompetensi Dasar (KD) ==</option>
                        </select>
                        @error('kd_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif
                <div class="row mb-2 {{($show && $show_tp) ? '' : 'd-none'}}">
                    <div class="col-6">
                        <input class="form-control" type="file" wire:model="template_excel">
                        @error('template_excel') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-6 d-grid">
                        <a class="btn btn-primary" href="{{route('unduhan.template-tp', ['id' => $cp_id])}}">Unduh Template TP</a>
                    </div>
                </div>
                <div class="row mb-2 {{($show && $show_kd) ? '' : 'd-none'}}">
                    <div class="col-6">
                        <input class="form-control" type="file" wire:model="template_excel">
                        @error('template_excel') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-6 d-grid">
                        <a class="btn btn-primary" href="{{route('unduhan.template-tp', ['id' => $kd_id])}}">Unduh Template TP</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('data_rombongan_belajar', event => {
        console.log(event.detail.data_rombongan_belajar);
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
    window.addEventListener('data_cp', event => {
        console.log(event.detail);
        $('#cp_id').html('<option value="">== Pilih Capaian Pembelajaran (CP) ==</option>')
        $.each(event.detail.data_cp, function (i, item) {
            $('#cp_id').append($('<option>', { 
                value: item.cp_id,
                text : '(Fase ' + item.fase + ') ' + item.deskripsi.substring(0,100) + '...'
            }));
        });
    })
    window.addEventListener('data_kd', event => {
        console.log(event.detail);
        $('#kd_id').html('<option value="">== Pilih Kompetensi Dasar (KD) ==</option>')
        $.each(event.detail.data_kd, function (i, item) {
            var kompetensi_dasar;
            if(item.kompetensi_dasar.length > 100){
                kompetensi_dasar = item.kompetensi_dasar.substring(0,100) + '...'
            } else {
                kompetensi_dasar = item.kompetensi_dasar
            }
            $('#kd_id').append($('<option>', { 
                value: item.kompetensi_dasar_id,
                text : '(' + item.id_kompetensi + ') ' + kompetensi_dasar
            }));
        });
    })
</script>
@endpush