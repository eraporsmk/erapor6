<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('components.navigasi-table')
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nama</th>
                            <th class="text-center">L/P</th>
                            <th class="text-center">Tempat, Tanggal Lahir</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Detil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($data_ptk->count())
                            @foreach($data_ptk as $ptk)
                            <tr>
                                <td>{{$ptk->nama_lengkap}}</td>
                                <td class="text-center">{{$ptk->jenis_kelamin}}</td>
                                <td>{{$ptk->tempat_lahir}}, {{$ptk->tanggal_lahir}}</td>
                                <td>{{$ptk->email}}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" wire:click="detil('{{$ptk->guru_id}}')">Detil</button>
                                </td>
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
                        @if($data_ptk->count())
                        <p>Menampilkan {{ $data_ptk->firstItem() }} sampai {{ $data_ptk->firstItem() + $data_ptk->count() - 1 }} dari {{ $data_ptk->total() }} data</p>
                        @endif
                    </div>
                    <div class="col-6">
                        {{ $data_ptk->onEachSide(1)->links('components.custom-pagination-links-view') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.referensi.modal.import-ptk')
    @include('livewire.referensi.modal.detil-guru')
    @include('components.loader')
</div>
@push('scripts')
<script>
    Livewire.on('showModal', event => {
        $('#ptkModal').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#ptkModal').modal('hide');
    })
    Livewire.on('detilGuru', event => {
        $('#detilGuru').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#detilGuru').modal('hide');
    })
    window.addEventListener('gelar_depan', event => {
        $('#gelar_depan').html('<option value="">== Pilih Gelar Depan ==</option>')
        $.each(event.detail.gelar_depan, function (i, item) {
            $('#gelar_depan').append($('<option>', { 
                value: item.gelar_akademik_id,
                text : item.kode
            }));
        });
    })
    window.addEventListener('gelar_belakang', event => {
        $('#gelar_belakang').html('<option value="">== Pilih Gelar Belakang ==</option>')
        $.each(event.detail.gelar_belakang, function (i, item) {
            $('#gelar_belakang').append($('<option>', { 
                value: item.gelar_akademik_id,
                text : item.kode
            }));
        });
    })
</script>
@endpush