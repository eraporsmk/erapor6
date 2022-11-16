<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <label for="semester_id" class="col-sm-3 col-form-label">Tahun Ajaran</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" readonly wire:model="semester_id">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="rencana_ukk_id" class="col-sm-3 col-form-label">Paket Kompetensi</label>
                    <div class="col-sm-9" wire:ignore>
                        <select id="rencana_ukk_id" class="form-select" wire:model="rencana_ukk_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Paket Kompetensi ==">
                            <option value="">== Pilih Paket Kompetensi ==</option>
                            @foreach ($rencana_ukk as $rencana)
                            <option value="{{$rencana->rencana_ukk_id}}">{{$rencana->paket_ukk->nama_paket_id}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if(count($data_siswa))
                <table class="table table-bordered tabel-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Nama Peserta Didik</th>
                            <th class="text-center">NISN</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Kesimpulan</th>
                            <th class="text-center">Cetak Sertifikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_siswa as $siswa)
                        <?php
                        $link_cetak = ($siswa->anggota_rombel->nilai_ukk_satuan && $siswa->anggota_rombel->nilai_ukk_satuan->nilai) ? '<a class="btn btn-sm btn-success" href="'.url('cetak/sertifikat/'.$siswa->anggota_rombel->anggota_rombel_id.'/'.$siswa->anggota_rombel->nilai_ukk_satuan->rencana_ukk_id).'" target="_blank">Cetak</a>' : '';
                        ?>
                        <tr>
                            <td>
                                {{$siswa->nama}}
                            </td>
                            <td class="text-center">{{$siswa->nisn}}</td>
                            <td>
                                <input type="number" wire:model.lazy="nilai_ukk.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="form-control form-control-sm">
                            </td>
                            <td>{{($siswa->anggota_rombel->nilai_ukk_satuan) ? keterangan_ukk($siswa->anggota_rombel->nilai_ukk_satuan->nilai) : ''}}</td>
                            <td>
                                {!! $link_cetak !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary {{(count($data_siswa)) ? '' : 'd-none'}}" wire:click.prevent="store()">Simpan</button>
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    /*window.addEventListener('paket_ukk', event => {
        console.log(event.detail.paket_ukk);
        $.each(event.detail.paket_ukk, function (i, item) {
            $('#rencana_ukk_id').append($('<option>', { 
                value: item.paket_ukk_id,
                text : item.nama_paket_id
            }));
        });
    })*/
</script>
@endpush
