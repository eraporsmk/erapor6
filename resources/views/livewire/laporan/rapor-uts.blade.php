<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:ignore.self wire:submit.prevent="store">
                <div class="card-body">
                    @role('waka', session('semester_id'))
                        @include('livewire.formulir-waka')
                    @endrole
                    @if($show)
                    <input type="hidden" wire:model="rombongan_belajar_id">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Mata Pelajaran</th>
                                <th class="text-center">Guru Mata Pelajaran</th>
                                <th class="text-center">Pilih Penilaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $check = 0; ?>
                            @forelse ($collection as $item)
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$item->rombongan_belajar->nama}}</td>
                                <td>{{$item->nama_mata_pelajaran}}</td>
                                <td>{{$item->guru->nama}}</td>
                                <td>
                                    <div wire:ignore.self>
                                        <select id="rencana_penilaian_{{$item->pembelajaran_id}}" class="form-select form-select-sm" data-pharaonic="select2" data-component-id="{{ $this->id }}" wire:model="rencana_penilaian.{{$item->pembelajaran_id}}" data-tags="true" data-placeholder="Pilih Penilaian" multiple>
                                            @foreach ($item->rencana_penilaian as $rencana_penilaian)
                                            <option value="{{$rencana_penilaian->rencana_penilaian_id}}">{{$rencana_penilaian->nama_penilaian}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            @if(count($item->rapor_pts))
                                <?php $check++; ?>
                            @endif
                            @empty
                            <tr>
                                <td class="text-center" colspan="5">Tidak ada data untuk ditampilkan</td>
                            </tr> 
                            @endforelse
                            @foreach ($collection as $item)
                            
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
                @if($show)
                <div class="card-footer d-md-flex justify-content-between">
                    @if($check)
                    <a target="_blank" class="btn btn-warning" href="{{url('cetak/rapor-uts/'.$rombongan_belajar_id)}}"><i class="fa fa-print"></i> Cetak</a>
                    @endif
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                @endif
            </form>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('rencana_penilaian', event => {
        //console.log(event.detail);
        $.each(event.detail.pembelajaran_id, function (urut, pembelajaran_id) {
            /*$('#rencana_penilaian_'+pembelajaran_id).append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));*/
            $('#rencana_penilaian_'+pembelajaran_id).html('<option value="">== Pilih Penilaian Update ==</option>')
            $.each(event.detail.rencana_penilaian, function (i, item) {
                if(item.rencana_penilaian.length){
                    $.each(item.rencana_penilaian, function (i, rencana_penilaian) {
                        $('#rencana_penilaian_'+pembelajaran_id).append($('<option>', { 
                            value: rencana_penilaian.rencana_penilaian_id,
                            text : rencana_penilaian.nama_penilaian
                        }));
                    });
                } else {
                    $('#rencana_penilaian_'+pembelajaran_id).val('')
                }
            });
            $.each(event.detail.rencana_penilaian_select, function (i, rencana_penilaian_select) {
                $.each(rencana_penilaian_select, function (i, rencana_penilaian_id) {
                    //console.log(rencana_penilaian_id);
                    $('#rencana_penilaian_'+pembelajaran_id).val(rencana_penilaian_id)
                });
            });
            console.log(event.detail.rencana_penilaian);
        });
        /*$('#rombongan_belajar_id').html('<option value="">== Pilih Penilaian ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });*/
    })
</script>
@endpush