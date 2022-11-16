<div>
    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Ubah Data Prestasi Peserta Didik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="tingkat" class="col-sm-3 col-form-label">Nama Peserta Didik</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="nama_pd">
                        </div>
                    </div>
                    <div class="row mb-2{{($show) ? '' : ' d-none'}}">
                        <label for="jenis_prestasi" class="col-sm-3 col-form-label">Jenis Prestasi</label>
                        <div class="col-sm-9" wire:ignore>
                            <select wire:model="jenis_prestasi" id="jenis_prestasi" class="form-select" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#editModal" data-placeholder="== Pilih Jenis Prestasi ==">
                                <option value="">== Pilih Jenis Prestasi ==</option>
                                <option value="Kurikuler">Kurikuler</option>
                                <option value="Ekstra Kurikuler">Ekstra Kurikuler</option>
                                <option value="Catatan Khusus Lainnya">Catatan Khusus Lainnya</option>
                            </select>
                            @error('jenis_prestasi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2{{($show) ? '' : ' d-none'}}">
                        <label for="keterangan_prestasi" class="col-sm-3 col-form-label">Keterangan Prestasi</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="keterangan_prestasi" rows="3" wire:model="keterangan_prestasi"></textarea>
                            @error('keterangan_prestasi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:target="perbaharui" wire:loading.remove>Tutup</button>
                    <div class="spinner-border text-primary" role="status" wire:loading wire:target="perbaharui">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="perbaharui" wire:target="perbaharui" wire:loading.remove>Perbaharui</button>
                </div>
            </div>
        </div>
    </div>
</div>