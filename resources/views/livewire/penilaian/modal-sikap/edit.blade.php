<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Nilai Sikap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <label for="butir_sikap" class="col-sm-3 col-form-label">Dimensi Sikap</label>
                    <div class="col-sm-9">
                        <div wire:ignore>
                            <select wire:model="budaya_kerja_id" class="form-control" id="budaya_kerja_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Dimensi Sikap ==">
                                <option value="">== Pilih Dimensi Sikap ==</option>
                                @foreach($all_sikap as $ref_sikap)
                                <option value="{{$ref_sikap->budaya_kerja_id}}">{{$ref_sikap->aspek}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('budaya_kerja_id') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="elemen_id" class="col-sm-3 col-form-label">Elemen Sikap</label>
                    <div class="col-sm-9">
                        <div wire:ignore>
                            <select wire:model="elemen_id" class="form-control" id="elemen_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Elemen Sikap ==">
                                <option value="">== Pilih Elemen Sikap ==</option>
                            </select>
                        </div>
                        @error('elemen_id') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="opsi_sikap" class="col-sm-3 col-form-label">Opsi Sikap</label>
                    <div class="col-sm-9">
                        <div wire:ignore>
                            <select wire:model="opsi_sikap" class="form-control" id="opsi_sikap" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Opsi Sikap ==">
                                <option value="">== Pilih Opsi Sikap ==</option>
                                <option value="1">Positif</option>
                                <option value="2">Negatif</option>
                            </select>
                        </div>
                        @error('opsi_sikap') <span class="text-danger">{{$message}}</span> @enderror
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
@push('scripts')
<script>
    window.addEventListener('data', event => {
        var nilai_budaya_kerja = event.detail.data;
        $('#budaya_kerja_id').val(nilai_budaya_kerja.budaya_kerja_id)
        $('#budaya_kerja_id').trigger('change')
        console.log(nilai_budaya_kerja);
        $("#elemen_id").html('<option value="">== Pilih Elemen Sikap ==</option>');
        $.each(event.detail.elemen_budaya_kerja, function (i, item) {
            $('#elemen_id').append($('<option>', { 
                value: item.elemen_id,
                text : item.elemen
            }));
        });
        $('#elemen_id').val(nilai_budaya_kerja.elemen_id)
        $('#elemen_id').trigger('change')
        $('#opsi_sikap').val(nilai_budaya_kerja.opsi_id)
        $('#opsi_sikap').trigger('change')
    })
</script>
@endpush