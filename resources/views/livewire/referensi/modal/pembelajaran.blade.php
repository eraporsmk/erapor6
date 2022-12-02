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
                                <th class="text-center" style="width: 3%">No</th>
                                <th class="text-center" style="width: 25%">Mata Pelajaran</th>
                                <th class="text-center" style="width: 9%">ID Mapel</th>
                                <th class="text-center" style="width: 15%">Guru Mapel (Dapodik)</th>
                                <th class="text-center" style="width: 20%">Guru Pengajar</th>
                                <th class="text-center" style="width: 20%">Kelompok</th>
                                <th class="text-center" style="width: 5%">No Urut</th>
                                <th class="text-center" style="width: 3%">Reset</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembelajaran as $urut => $item)
                            <tr>
                                <td class="text-center align-top">{{$urut + 1}}</td>
                                <td class="align-top">
                                    <input type="text" wire:model.defer="nama_mata_pelajaran.{{$item->pembelajaran_id}}" class="form-control @error('nama_mata_pelajaran.'.$item->pembelajaran_id) is-invalid @enderror">
                                    @error('nama_mata_pelajaran.'.$item->pembelajaran_id) {{$message}} @enderror
                                </td>
                                <td class="align-top">
                                    <input type="text" class="form-control" value="{{$item->mata_pelajaran_id}}" readonly>
                                </td>
                                <td class="align-top">
                                    <input type="text" class="form-control" value="{{$item->guru->nama_lengkap}}" readonly>
                                </td>
                                <td class="align-top">
                                    <div wire:ignore>
                                        <select id="pengajar_{{$urut}}" class="form-select" wire:model.defer="pengajar.{{$item->pembelajaran_id}}" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Guru Pengajar ==" data-parent="#pembelajaranModal" data-clear="true">
                                            <option value="">== Pilih Guru Pengajar ==</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="align-top">
                                    <div wire:ignore>
                                        <select id="kelompok_id_{{$urut}}" class="form-select" wire:model.defer="kelompok_id.{{$item->pembelajaran_id}}" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Kelompok ==" data-parent="#pembelajaranModal" data-clear="true">
                                            <option value="">== Pilih Kelompok ==</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="align-top text-center">
                                    <input type="text" class="form-control text-center @error('no_urut.'.$item->pembelajaran_id) is-invalid @enderror" wire:model.defer="no_urut.{{$item->pembelajaran_id}}">
                                    @error('no_urut.'.$item->pembelajaran_id) {{$message}} @enderror
                                </td>
                                <td class="text-center">
                                    @if($item->kelompok_id || $item->no_urut)
                                    <a href="javascript:void(0)" wire:click="hapusPembelajaran('{{$item->pembelajaran_id}}')"><i class="fas fa-trash text-danger text-lg"></i></a>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="7">
                                    <div class="alert alert-danger" role="alert">
                                        <div class="alert-body text-center">
                                            <h2>Tidak ada data untuk ditampilkan</h2>
                                            <p>Pastikan Data Pembelajaran telah di input di Aplikasi Dapodik!</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
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