<div>
    <div wire:ignore.self class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Unit Kompetensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="jurusan_id" class="col-sm-3 col-form-label">Kompetensi Keahlian</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="tingkat" class="form-select" wire:model="jurusan_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#tambahModal" data-placeholder="== Pilih Kompetensi Keahlian ==">
                                <option value="">== Pilih Kompetensi Keahlian ==</option>
                                @foreach ($all_jurusan as $jurusan)
                                <option value="{{$jurusan->jurusan_id}}">{{$jurusan->nama_jurusan_sp}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="kurikulum_id" class="col-sm-3 col-form-label">Kurikulum</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="kurikulum_id" class="form-select" wire:model="kurikulum_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#tambahModal" data-placeholder="== Pilih Kurikulum ==">
                                <option value="">== Pilih Kurikulum ==</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Nomor Paket</th>
                                    <th class="text-center">Judul Paket (ID)</th>
                                    <th class="text-center">Judul Paket (EN)</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < $jml; $i++)
                                <tr>
                                    <td><input type="text" class="form-control" wire:model="all_nomor_paket.{{$i}}"></td>
                                    <td><input type="text" class="form-control" wire:model="nama_paket_id.{{$i}}"></td>
                                    <td><input type="text" class="form-control" wire:model="nama_paket_en.{{$i}}"></td>
                                    <td>
                                        <select id="status-{{$i}}" class="form-select" wire:model="status.{{$i}}">
                                            <option value="">== Pilih Status ==</option>
                                            <option value="1" selected>Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                                    </td>
                                </tr> 
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="$emit('postAdded')" class="btn btn-danger mr-auto">Tambah Form</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="store()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>