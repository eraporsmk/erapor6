<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @role(['waka', 'tu'], session('semester_id'))
                    @include('livewire.formulir-waka')
                    @if($show && $data_pembelajaran->count())
                    <div class="row mb-2">
                        <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Unduh Legger</label>
                        <div class="col-sm-9">
                            <a href="{{route('unduhan.unduh-leger-nilai-kurmer', ['rombongan_belajar_id' => $rombongan_belajar_id])}}" class="btn btn-success">Unduh Legger</a>
                        </div>
                    </div>
                    @endif
                @endrole
                @if($show)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nama Peserta Didik</th>
                            <th class="text-center">NISN</th>
                            @foreach ($data_pembelajaran as $pembelajaran)
                            <th class="text-center">{{$pembelajaran->nama_mata_pelajaran}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_siswa as $item)
                        <tr>
                            <td>{{$item->nama}}</td>
                            <td class="text-center">{{$item->nisn}}</td>
                            @foreach ($data_pembelajaran as $pembelajaran)
                            <?php
                            if($merdeka){
                                $nilai = $pembelajaran->nilai_akhir_kurmer()->where('anggota_rombel_id', $item->anggota_rombel->anggota_rombel_id)->first();
                            } else {
                                $nilai = $pembelajaran->nilai_akhir_pengetahuan()->where('anggota_rombel_id', $item->anggota_rombel->anggota_rombel_id)->first();
                            }
                            ?>
                            <td class="text-center">{{($nilai) ? $nilai->nilai : '-'}}</td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="{{($data_pembelajaran) ? ($data_pembelajaran->count() + 2) : 2}}">Tidak ada data untuk ditampilkan</td>
                        </tr> 
                        @endforelse
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
