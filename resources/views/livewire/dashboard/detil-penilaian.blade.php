<div>
    <div wire:ignore.self class="modal fade" id="detilModal" tabindex="-1" aria-labelledby="detilModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detilModalLabel">Detil Penilaian Mata Pelajaran {{$nama_mata_pelajaran}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">NISN</th>
                                <th class="text-center">Agama</th>
                                <th class="text-center">Nilai Akhir</th>
                                <th class="text-center">Capaian Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_siswa as $siswa)
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$siswa->nama}}</td>
                                <td class="text-center">{{$siswa->nisn}}</td>
                                <td class="text-center">{{$siswa->agama->nama}}</td>
                                <td class="text-center">
                                    @if($merdeka)
                                    {{($siswa->nilai_akhir_kurmer) ? $siswa->nilai_akhir_kurmer->nilai : '-'}}
                                    @else
                                    {{($siswa->nilai_akhir_pengetahuan) ? $siswa->nilai_akhir_pengetahuan->nilai : '-'}}
                                    @endif
                                </td>
                                <td>
                                    @if($siswa->deskripsi_mapel)
                                    @if($siswa->deskripsi_mapel->deskripsi_pengetahuan && $siswa->deskripsi_mapel->deskripsi_keterampilan)
                                    {!!  $siswa->deskripsi_mapel->deskripsi_pengetahuan !!}
                                    <hr>
                                    {!! $siswa->deskripsi_mapel->deskripsi_keterampilan !!}
                                    @endif
                                    @if($siswa->deskripsi_mapel->deskripsi_pengetahuan && !$siswa->deskripsi_mapel->deskripsi_keterampilan)
                                    {!!  $siswa->deskripsi_mapel->deskripsi_pengetahuan !!}
                                    @endif
                                    @if(!$siswa->deskripsi_mapel->deskripsi_pengetahuan && $siswa->deskripsi_mapel->deskripsi_keterampilan)
                                    {!!  $siswa->deskripsi_mapel->deskripsi_keterampilan !!}
                                    @endif
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="6">Tidak ada data untuk ditampilkan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
