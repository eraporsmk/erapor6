<div>
    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Perbaharui Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <td width="30%">Kompetensi Keahlian</td>
                            <td width="70%">{{($paket_ukk) ? $paket_ukk->jurusan->nama_jurusan : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Kurikulum</td>
                            <td>{{($paket_ukk) ? $paket_ukk->kurikulum->nama_kurikulum : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Nomor Paket</td>
                            <td>
                                <input type="text" class="form-control" wire:model.lazy="nomor_paket_satuan">
                                @error('nomor_paket_satuan') <div class="text-danger">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Judul Paket (ID)</td>
                            <td>
                                <input type="text" class="form-control" wire:model.lazy="nama_paket_id_satuan">
                                @error('nama_paket_id_satuan') <div class="text-danger">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Judul Paket (EN)</td>
                            <td>
                                <input type="text" class="form-control" wire:model.lazy="nama_paket_en_satuan">
                                @error('nama_paket_en_satuan') <div class="text-danger">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <select id="status" class="form-select" wire:model="status_satuan">
                                    <option value="">== Pilih Status ==</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                                @error('status_satuan') <div class="text-danger">{{$message}}</div> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Unit UKK</td>
                            <td>
                                @if($paket_ukk_satuan)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="tex-center">Kode Unit</th>
                                            <th class="tex-center">Nama Unit Kompetensi</th>
                                            <th class="tex-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($paket_ukk_satuan as $unit)
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control" wire:model.defer="kode_unit_satuan.{{$unit->unit_ukk_id}}">
                                                    @error('kode_unit_satuan.'.$unit->unit_ukk_id) <div class="text-danger">{{$message}}</div> @enderror
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" wire:model.defer="nama_unit_satuan.{{$unit->unit_ukk_id}}">
                                                    @error('nama_unit_satuan.'.$unit->unit_ukk_id) <div class="text-danger">{{$message}}</div> @enderror
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0)" wire:click="deleteUnit('{{$unit->unit_ukk_id}}')" class="text-danger"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="perbaharui()">Perbaharui</button>
                </div>
            </div>
        </div>
    </div>
</div>
