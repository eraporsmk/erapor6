<div>
    <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Unit Kompetensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="nama_jurusan" class="col-sm-3 col-form-label">Kompetensi Keahlian</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="nama_jurusan">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kode_kompetensi" class="col-sm-3 col-form-label">Kode Kompetensi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="kode_kompetensi">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="nomor_paket" class="col-sm-3 col-form-label">Nomor Paket</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="nomor_paket">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="judul_paket" class="col-sm-3 col-form-label">Judul Paket</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="judul_paket">
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" width="30%">Kode Unit</th>
                                <th class="text-center" width="70%">Nama Unit Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collection_unit as $item_unit)
                            <tr>
                                <td><input type="text" class="form-control" readonly value="{{$item_unit->kode_unit}}"></td>
                                <td><input type="text" class="form-control" readonly value="{{$item_unit->nama_unit}}"></td>
                            </tr>
                            @endforeach
                            @for ($i = 0; $i < $jml; $i++)
                            <tr>
                                <td><input type="text" class="form-control" wire:model.defer="kode_unit.{{$i}}"></td>
                                <td><input type="text" class="form-control" wire:model.defer="nama_unit.{{$i}}"></td>
                            </tr> 
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button wire:click="$emit('postAdded')" class="btn btn-danger mr-auto">Tambah Form</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="store_unit()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
