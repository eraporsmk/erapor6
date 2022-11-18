<div>
    <div wire:ignore.self class="modal fade" id="pembelajaranModal" tabindex="-1" aria-labelledby="pembelajaranModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pembelajaranModalLabel">Pembelajaran Kelas {{$nama_kelas}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Mata Pelajaran</th>
                                <th class="text-center">Guru Mapel (Dapodik)</th>
                                <th class="text-center">Guru Pengajar</th>
                                <th class="text-center">Kelompok</th>
                                <th class="text-center">No Urut</th>
                                <th class="text-center">Reset</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembelajaran as $urut => $item)
                                <tr>
                                    <td class="text-center">{{$urut + 1}}</td>
                                    <td>
                                        <input type="text" wire:model.lazy="nama_mata_pelajaran.{{$item->pembelajaran_id}}" class="form-control">
                                    </td>
                                    <td>{{$item->guru->nama_lengkap}}</td>
                                    <td>
                                        <div wire:ignore>
                                            <select id="pengajar_{{$item->pembelajaran_id}}" class="form-select" wire:model="pengajar.{{$item->pembelajaran_id}}" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Guru Pengajar ==" data-parent="#pembelajaranModal">
                                                <option value="">== Pilih Guru Pengajar ==</option>
                                                {{--
                                                @foreach ($guru_pengajar as $pengajar)
                                                    <option value="{{$pengajar->guru_id}}">{{$pengajar->nama_lengkap}}</option>
                                                @endforeach
                                                --}}
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div wire:ignore>
                                            <select id="kelompok_id_{{$item->pembelajaran_id}}" class="form-select" wire:model="kelompok_id.{{$item->pembelajaran_id}}" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Kelompok ==" data-parent="#pembelajaranModal">
                                                <option value="">== Pilih Guru Pengajar ==</option>
                                                {{--
                                                @foreach ($data_kelompok as $kelompok)
                                                    <option value="{{$kelompok->kelompok_id}}">{{$kelompok->nama_kelompok}}</option>
                                                @endforeach
                                                --}}
                                            </select>
                                        </div>
                                    </td>
                                    <td><input type="number" class="form-control" wire:model="no_urut.{{$item->pembelajaran_id}}"></td>
                                    <td class="text-center">
                                        @if($item->kelompok_id)
                                        <a href="javascript:void(0)" wire:click="hapusPembelajaran('{{$item->pembelajaran_id}}')"><i class="fas fa-trash text-danger text-lg"></i></a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:target="simpanPembelajaran" wire:loading.remove>Tutup</button>
                    <div class="spinner-border text-primary" role="status" wire:loading wire:target="simpanPembelajaran">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="simpanPembelajaran" wire:target="simpanPembelajaran" wire:loading.remove>Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>