<div wire:ignore.self class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Detil Data Perencanaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <td>Mata Pelajaran</td>
                            <td>{{($rencana) ? $rencana->pembelajaran->nama_mata_pelajaran : ''}}</td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>{{($rencana) ? $rencana->rombongan_belajar->nama : ''}}</td>
                        </tr>
                        <tr>
                            <td>Aktifitas Penilaian</td>
                            <td>{{($rencana) ? $rencana->nama_penilaian : ''}}</td>
                        </tr>
                        <tr>
                            <td>Teknik Penilaian</td>
                            <td>{{($rencana) ? $rencana->teknik_penilaian->nama : ''}}</td>
                        </tr>
                        <tr>
                            <td>Bobot</td>
                            <td>{{($rencana) ? $rencana->bobot : ''}}</td>
                        </tr>
                        <tr>
                            <td>KD/CP Dipilih</td>
                            <td>
                                @if ($rencana && $rencana->kd_nilai->count())
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Kode</th>
                                                <th class="text-center">Isi Kompetensi Dasar/Capaian Pembelajaran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rencana->kd_nilai as $item)
                                            <tr>
                                                <td>{{$item->id_kompetensi}}</td>
                                                <td>{{($item->kompetensi_dasar->kompetensi_dasar_alias) ?? $item->kompetensi_dasar->kompetensi_dasar}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                Tidak ada KD/CP Dipilih
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>