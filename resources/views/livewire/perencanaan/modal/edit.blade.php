<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Perencanaan</h5>
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
                            <td>Kompetensi Dasar Dipilih</td>
                            <td>
                                @if ($rencana && $rencana->kd_nilai->count())
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Kode</th>
                                                <th class="text-center">Deskripsi Kompetensi Dasar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_kd as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" wire:model="kd_select.{{$item->kompetensi_dasar_id}}" id="flexCheckDefault">
                                                    </div>
                                                </td>
                                                <td>{{$item->id_kompetensi}}</td>
                                                <td>{{($item->kompetensi_dasar_alias) ?? $item->kompetensi_dasar}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                Tidak ada Kompetensi Dasar Dipilih
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="perbaharui()">Perbaharui</button>
                </div>
            </div>
        </div>
    </div>