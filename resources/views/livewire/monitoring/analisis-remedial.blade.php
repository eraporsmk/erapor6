<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('livewire.formulir-remedial')
                @if($show)
                <h3>Rekapitulasi Hasil Penilaian Remedial</h3>
                <table class="table table-bordered table-striped table-hover jarak1">
                    <thead>
                        <tr>
                            <th class="text-center" width="1%" rowspan="2" style="vertical-align:middle;">No</th>
                            <th style="width: 40%; vertical-align:middle;" rowspan="2">Nama Peserta Didik</th>
                            <th style="width: 10%" class="text-center" colspan="{{count($pembelajaran->kd_nilai)}}">Kompetensi Dasar</th>
                            <th style="width: 10%; vertical-align:middle;" class="text-center" rowspan="2">Rerata Akhir</th>
                            <th style="width: 10%; vertical-align:middle;" class="text-center" rowspan="2">Rerata Remedial</th>
                        </tr>
                        <tr>
                        @foreach($data_kd as $kd_nilai)
                            <th class="text-center">{{$kd_nilai->id_kompetensi}}</th>
                        @endforeach
                        </tr>
                    </thead>
                    <tbody>
                    @if($pembelajaran->anggota_rombel->count())
                    @foreach($pembelajaran->anggota_rombel as $anggota_rombel)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{($anggota_rombel->peserta_didik) ? $anggota_rombel->peserta_didik->nama : '-'}}</td>
                            <?php $r_remedial = 0; ?>
                            @if($anggota_rombel->nilai_remedial)
                                <?php
                                $nilai_remedial = unserialize($anggota_rombel->nilai_remedial->nilai); 
                                $r_remedial = array_sum($nilai_remedial)/count($nilai_remedial);
                                ?>
                                @foreach($nilai_remedial as $remedial)
                                <td class="text-center">{{$remedial}}</td>
                                @endforeach
                            @else
                                @foreach($data_kd as $kd_nilai)
                                <td class="text-center">-</td>
                                @endforeach
                            @endif
                            <?php
                            $a = ($anggota_rombel->{$with}) ? $anggota_rombel->{$with}->where('anggota_rombel_id', $anggota_rombel->anggota_rombel_id)->where('pembelajaran_id', $pembelajaran->pembelajaran_id)->where('kompetensi_id', $kompetensi_id)->first() : NULL;
                            if($a){
                                $nilai_akhir = $a->nilai_akhir;
                            } else {
                                $nilai_akhir = 0;
                            }
                            ?>
                            <td class="text-center">{{$nilai_akhir}}</td>
                            <td class="text-center">{{number_format($r_remedial,0)}}</td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="6">Tidak ada data untuk ditampilkan</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                @endif
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    @include('components.loader')
</div>
