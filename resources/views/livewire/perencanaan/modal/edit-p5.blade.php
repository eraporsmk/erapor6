<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Perencanaan P5</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <td>Kelas</td>
                            <td>{{($projek) ? $projek->rombongan_belajar->nama : ''}}</td>
                        </tr>
                        <tr>
                            <td>Tema</td>
                            <td>{{($projek && $projek->pembelajaran) ? $projek->pembelajaran->nama_mata_pelajaran : ''}}</td>
                        </tr>
                        <tr>
                            <td>Nama Projek</td>
                            <td>
                                <input class="form-control" type="text" id="nama_projek" wire:model="nama_projek">
                            </td>
                        </tr>
                        <tr>
                            <td>Deskripsi Projek</td>
                            <td>
                                <textarea class="form-control" id="deskripsi" wire:model="deskripsi"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Elemen/Sub Elemen</td>
                            <td>
                                @if ($projek && $projek->aspek_budaya_kerja->count())
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Dimensi</th>
                                                <th class="text-center">Elemen</th>
                                                <th class="text-center">Sub Elemen</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($projek->aspek_budaya_kerja as $aspek_budaya_kerja)
                                            <tr>
                                                <td>{{$aspek_budaya_kerja->budaya_kerja->aspek}}</td>
                                                <td>{{$aspek_budaya_kerja->elemen_budaya_kerja->elemen}}</td>
                                                <td>{{$aspek_budaya_kerja->elemen_budaya_kerja->deskripsi}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                Tidak ada Elemen/Sub Elemen Dipilih
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