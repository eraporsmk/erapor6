<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Nama</th>
                                <th class="text-center align-middle">Wali Kelas</th>
                                <th class="text-center align-middle">Tingkat</th>
                                <th class="text-center align-middle">Program/Kompetensi Keahlian</th>
                                <th class="text-center align-middle">Kurikulum</th>
                                <th class="text-center align-middle">Anggota Rombel</th>
                                <th class="text-center align-middle">Pembelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($collection->count())
                                @foreach($collection as $item)
                                <tr>
                                    <td>{{$item->nama}}</td>
                                    <td>{{$item->wali_kelas->nama}}</td>
                                    <td class="text-center">{{$item->tingkat}}</td>
                                    <td>{{($item->jurusan_sp) ? $item->jurusan_sp->nama_jurusan_sp : '-'}}</td>
                                    <td>{{$item->kurikulum->nama_kurikulum}}</td>
                                    <td>
                                        <div class="d-grid">
                                            <button type="button" class="btn btn-sm btn-success waves-effect waves-float waves-light" data-bs-toggle="modal" data-bs-target="#anggotaRombelModal" wire:click="getAnggota('{{$item->rombongan_belajar_id}}')">Anggota Rombel</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            <button type="button" class="btn btn-sm btn-success waves-effect waves-float waves-light" wire:click="getPembelajaran('{{$item->rombongan_belajar_id}}')">Pembelajaran</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="7">Tidak ada data untuk ditampilkan</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
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
    @include('livewire.referensi.modal.anggota-rombel')
    @include('livewire.referensi.modal.pembelajaran')
    @include('components.loader')
</div>
@push('scripts')
<script>
    Livewire.on('show-pembelajaran', event => {
        $('#pembelajaranModal').modal('show');
    })
    window.addEventListener('pembelajaran', event => {
        console.log(event.detail);
        $.each(event.detail.pembelajaran_id, function (i, pembelajaran_id) {
            $.each(event.detail.data_kelompok, function (i, item) {
                $('#kelompok_id_'+pembelajaran_id).append($('<option>', { 
                    value: item.kelompok_id,
                    text : item.nama_kelompok,
                }));
            });
            $.each(event.detail.kelompok_id, function (i, item) {
                $('#kelompok_id_'+i).val(item)
            });
            $.each(event.detail.guru_pengajar, function (i, item) {
                $('#pengajar_'+pembelajaran_id).append($('<option>', { 
                    value: item.guru_id,
                    text : item.nama_lengkap,
                }));
            });
            if(event.detail.pengajar.constructor.name === 'Object'){
                $.each(event.detail.pengajar, function (i, item) {
                    $('#pengajar_'+i).val(item)
                });
            } else {
                var a = $('#pengajar_'+pembelajaran_id).val('')
                console.log(pembelajaran_id);
                console.log(a);
            }
        });
        /**/
    })
</script>
@endpush