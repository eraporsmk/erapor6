<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Nilai Sikap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <label for="sikap_id" class="col-sm-3 col-form-label">Butir Sikap</label>
                    <div class="col-sm-9">
                        <select wire:model="sikap_id" class="form-control" id="sikap_id">
                            <option value="">== Pilih Butir Sikap ==</option>
                            @foreach($all_sikap as $ref_sikap)
                            <option value="{{$ref_sikap->sikap_id}}">{{$ref_sikap->butir_sikap}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="opsi_sikap" class="col-sm-3 col-form-label">Opsi Sikap</label>
                    <div class="col-sm-9">
                        <select wire:model="opsi_sikap" class="form-control" id="opsi_sikap">
                            <option value="">== Pilih Opsi Sikap ==</option>
                            <option value="1">Positif</option>
                            <option value="2">Negatif</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="uraian_sikap" class="col-sm-3 col-form-label">Uraian Sikap</label>
                    <div class="col-sm-9">
                        <textarea wire:model.lazy="uraian_sikap" id="uraian_sikap" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" wire:click.prevent="update()">Perbaharui</button>
            </div>
        </div>
    </div>
</div>