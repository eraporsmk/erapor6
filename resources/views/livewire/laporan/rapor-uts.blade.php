<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:ignore.self wire:submit.prevent="store">
                <div class="card-body">
                    <input type="hidden" wire:model="rombongan_belajar_id">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>a</th>
                                <th class="text-center">Mata Pelajaran</th>
                                <th class="text-center">Guru Mata Pelajaran</th>
                                <th class="text-center">Pilih Penilaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $check = 0; ?>
                            @foreach ($collection as $item)
                            @if(count($item->rapor_pts))
                                <?php $check++; ?>
                            @endif
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$item->rombongan_belajar->nama}}</td>
                                <td>{{$item->nama_mata_pelajaran}}</td>
                                <td>{{$item->guru->nama}}</td>
                                <td>
                                    <div wire:ignore>
                                        <select class="form-select form-select-sm" data-pharaonic="select2" data-component-id="{{ $this->id }}" wire:model="rencana_penilaian.{{$item->pembelajaran_id}}" data-tags="true" data-placeholder="Pilih Penilaian" multiple>
                                            @foreach ($item->rencana_penilaian as $rencana_penilaian)
                                            <option value="{{$rencana_penilaian->rencana_penilaian_id}}">{{$rencana_penilaian->nama_penilaian}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-md-flex justify-content-between">
                    @if($check)
                    <a target="_blank" class="btn btn-warning" href="{{url('cetak/rapor-uts/'.$rombongan_belajar_id)}}"><i class="fa fa-print"></i> Cetak</a>
                    @endif
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
