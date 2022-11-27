<div>
    <div wire:ignore.self class="modal fade" id="copyModal" tabindex="-1" aria-labelledby="copyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="copyModalLabel">Duplikasi Data Perencanaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Rombongan Belajar</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="rombongan_belajar_id_copy" class="form-select" wire:model="rombongan_belajar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#copyModal" data-placeholder="== Pilih Rombongan Belajar ==" wire:change="changeRombel">
                                <option value="">== Pilih Rombongan Belajar ==</option>
                            </select>
                        </div>
                    </div>
                    {{--
                    <div class="row mb-2">
                        <label for="mata_pelajaran_id" class="col-sm-3 col-form-label">Mata Pelajaran</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="pembelajaran_id_copy" class="form-select" wire:model="pembelajaran_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#copyModal" data-placeholder="== Pilih Mata Pelajaran ==" wire:change="changePembelajaran">
                                <option value="">== Pilih Mata Pelajaran ==</option>
                            </select>
                        </div>
                    </div>
                    --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="duplikasi()">Duplikasi</button>
                </div>
            </div>
        </div>
    </div>
</div>
