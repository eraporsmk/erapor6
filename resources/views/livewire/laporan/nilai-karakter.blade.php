<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nama Peserta Didik</th>
                            <th class="text-center">Capaian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td>{{$item->peserta_didik->nama}}</td>
                                <td>{{$item->capaian}}</td>
                                <td class="text-center">
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getID('{{$item->catatan_ppk_id}}', 'view')" title="Lihat Detil"><i class="fas fa-eye"></i> Detil</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" wire:click="getID('{{$item->catatan_ppk_id}}', 'hapus')" title="Hapus Data"><i class="fas fa-trash"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="3">Tidak ada data untuk ditampilkan</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="row justify-content-between mt-2">
                    <div class="col-6">
                        @if($collection->count())
                        <p>Menampilkan {{ $collection->firstItem() }} sampai {{ $collection->firstItem() + $collection->count() - 1 }} dari {{ $collection->total() }} data</p>
                        @endif
                    </div>
                    <div class="col-6">
                        {{ $collection->onEachSide(1)->links('components.custom-pagination-links-view') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Nilai Karakter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:ignore.self wire:submit.prevent="store">
                <div class="modal-body">
                    <div class="row mb-2">
                        <label for="anggota_rombel_id" class="col-sm-3 col-form-label">Nama Peserta Didik</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="anggota_rombel_id" class="form-select" wire:model="anggota_rombel_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Siswa ==" wire:change="changeSiswa">
                                <option value="">== Pilih Siswa ==</option>
                                @foreach ($data_siswa as $siswa)
                                <option value="{{$siswa->anggota_rombel->anggota_rombel_id}}">{{$siswa->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-3 col-form-label">Catatan Penilaian Sikap Integritas <br> <strong>{{$nama_siswa}}</strong></label>
                        <div class="col-sm-9">
                            Catatan nilai sikap dari guru:
                            @if(count($nilai_sikap))
                            <ul class="ps-1">
                            @foreach($nilai_sikap as $ns)
                            <li>{{$ns->guru->nama}} <i class="fa-solid fa-hand-point-right"></i> {{$ns->uraian_sikap}} ({{$ns->opsi_sikap}})</li>
                            @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                    @foreach($all_sikap as $key => $sikap)
                    <div class="row mb-2">
                        <label for="anggota_wirausaha_id" class="col-sm-3 control-label">
                            {{$sikap->butir_sikap}}
                            <ul class="ps-1">
                                @foreach($sikap->sikap as $sub_sikap)
                                <li>{{$sub_sikap->butir_sikap}}</li>
                                @endforeach
                            </ul>
                        </label>
                        <div class="col-sm-9">
                            <textarea wire:ignore.self wire:model.lazy="deskripsi.{{$sikap->sikap_id}}" id="deskripsi" class="form-control"></textarea>
                        </div>
                    </div>
                    @endforeach
                    <div class="row mb-2">
                        <label for="capaian" class="col-sm-3 control-label">Catatan Perkembangan Karakter</label>
                        <div class="col-sm-9">
                            <textarea wire:ignore.self wire:model.lazy="capaian" id="capaian" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary{{($show) ? '' : ' d-none'}}">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Detil Nilai Karakter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody><tr>
                            <td width="25%">Nama Peserta Didik</td>
                            <td class="text-center" width="1%">:</td>
                            <td width="74%">{{($catatan_ppk) ? $catatan_ppk->peserta_didik->nama : ''}}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align:middle;">Catatan Perkembangan Karakter</td>
                            <td style="vertical-align:middle;">:</td>
                            <td>{{($catatan_ppk) ? $catatan_ppk->capaian : ''}}</td>
                        </tr>
                        @if($catatan_ppk)
                        @forelse ($catatan_ppk->nilai_karakter as $nilai_karakter)
                        <tr>
                            <td style="vertical-align:middle;">Catatan Penilaian Sikap {{$nilai_karakter->sikap->butir_sikap}}</td>
                            <td style="vertical-align:middle;">:</td>
                            <td>{{$nilai_karakter->deskripsi}}</td>
                        </tr>
                        @empty
                            
                        @endforelse
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('anggota_wirausaha', event => {
        $.each(event.detail.anggota_wirausaha, function (i, item) {
            $('#anggota_wirausaha_id').append($('<option>', { 
                value: item.anggota_rombel.anggota_rombel_id,
                text : item.nama
            }));
        });
    })
    var addModalListener = document.getElementById('addModal')
    var addModal = new bootstrap.Modal(addModalListener, {
        keyboard: true
    })

    addModalListener.addEventListener('hidden.bs.modal', function (event) {
        Livewire.emit('cancel')
    })
    var ids = ['#anggota_rombel_id', '#pola', '#anggota_wirausaha_id', '#jenis_usaha', '#nama_produk'];
    Livewire.on('showModal', event => {
        $.each(ids, function (i, item) {
            $(item).val('');
            $(item).trigger('change');
        })
        addModal.show()
        addModal.handleUpdate()
    })
    Livewire.on('close-modal', event => {
        $('#addModal').modal('hide');
        $('#viewModal').modal('hide');
    })
    Livewire.on('show-modal', event => {
        $('#viewModal').modal('show');
    })
</script>
@endpush