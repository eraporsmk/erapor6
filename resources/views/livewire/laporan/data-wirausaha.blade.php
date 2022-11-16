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
                            <th class="text-center">Pola Kewirausahaan</th>
                            <th class="text-center">Jenis Kewirausahaan</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($collection->count())
                            @foreach($collection as $item)
                            <tr>
                                <td>
                                    @if($item->anggota_kewirausahaan)
                                    {!! $item->peserta_didik->nama.'<br>'.implode('<br>', $item->anggota_kewirausahaan->map(function ($anggota) {
                                        return $anggota->peserta_didik->nama;
                                    })->toArray()) !!}
                                    @else
                                    {{$item->peserta_didik->nama}}
                                    @endif
                                </td>
                                <td class="text-center">{{$item->pola}}</td>
                                <td>{{$item->jenis}}</td>
                                <td>{{$item->nama_produk}}</td>
                                <td>aksi</td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="5">Tidak ada data untuk ditampilkan</td>
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
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Kewirausahaan</h5>
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
                    <div class="row mb-2{{($show) ? '' : ' d-none'}}">
                        <label for="pola" class="col-sm-3 col-form-label">Pola Kewirausahaan</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="pola" class="form-select" wire:model="pola" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Pola Kewirausahaan ==" wire:change="changePola">
                                <option value="">== Pilih Pola Kewirausahaan ==</option>
                                <option value="Individu">Individu</option>
                                <option value="Kelompok">Kelompok</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2{{($kelompok) ? '' : ' d-none'}}">
                        <label for="anggota_wirausaha_id" class="col-sm-3 control-label">Anggota Kewirausahaan</label>
                        <div class="col-sm-9" wire:ignore>
                            <select wire:model="anggota_wirausaha_id" id="anggota_wirausaha_id" class="form-control" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Pola Kewirausahaan ==" multiple="multiple" data-tags="true">
                                <option value="">== Pilih Anggota Kewirausahaan ==</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2{{($show) ? '' : ' d-none'}}">
                        <label for="jenis_usaha" class="col-sm-3 col-form-label">Jenis Kewirausahaan</label>
                        <div class="col-sm-9" wire:ignore>
                            <select id="jenis_usaha" class="form-select" wire:model="jenis_usaha" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-parent="#addModal" data-placeholder="== Pilih Jenis Kewirausahaan ==">
                                <option value="">== Pilih Jenis Kewirausahaan ==</option>
                                <option value="Jasa">Jasa</option>
                                <option value="Produk">Produk</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2{{($show) ? '' : ' d-none'}}">
                        <label for="nama_produk" class="col-sm-3 control-label">Nama Produk Kewirausahaan</label>
                        <div class="col-sm-9">
                            <input type="text" wire:model="nama_produk" id="nama_produk" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer{{($show) ? '' : ' d-none'}}">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
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
    var addModal = document.getElementById('addModal')
    addModal.addEventListener('hidden.bs.modal', function (event) {
        Livewire.emit('cancel')
    })
    var ids = ['#anggota_rombel_id', '#pola', '#anggota_wirausaha_id', '#jenis_usaha', '#nama_produk'];
    Livewire.on('showModal', event => {
        $.each(ids, function (i, item) {
            $(item).val('');
            $(item).trigger('change');
        })
        $('#addModal').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#addModal').modal('hide');
    })
</script>
@endpush