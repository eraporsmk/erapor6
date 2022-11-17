<div>
    <div wire:ignore.self class="modal fade" id="editKd" tabindex="-1" aria-labelledby="editKdLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKdLabel">Ubah Ringkasan Kompetensi Dasar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="nama_jurusan" class="col-sm-3 col-form-label">Ringkasan Kompetensi Dasar Lama</label>
                        <div class="col-sm-9">
                            <textarea wire:ignore wire:model="kd_lama" class="textarea form-control" rows="5" readonly></textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kode_kompetensi" class="col-sm-3 col-form-label">Ringkasan Kompetensi Dasar Baru</label>
                        <div class="col-sm-9">
                            <textarea wire:ignore wire:model="kd_baru" class="textarea form-control" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="store()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
