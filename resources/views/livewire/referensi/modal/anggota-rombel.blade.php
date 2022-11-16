<div>
    <div wire:ignore.self class="modal fade" id="anggotaRombelModal" tabindex="-1" aria-labelledby="anggotaRombelModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="anggotaRombelModalLabel">Anggota Rombel Kelas {{$nama_kelas}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">NISN</th>
                                <th class="text-center">L/P</th>
                                <th class="text-center">Tempat, Tanggal Lahir</th>
                                <th class="text-center">Agama</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($anggota_rombel as $no => $item)
                            <tr>
                                <td class="text-center">{{$no + 1}}</td>
                                <td>{{$item->nama}}</td>
                                <td class="text-center">{{$item->nisn}}</td>
                                <td class="text-center">{{$item->jenis_kelamin}}</td>
                                <td>{{$item->tempat_lahir}}, {{$item->tanggal_lahir}}</td>
                                <td>{{$item->agama->nama}}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger waves-effect waves-float waves-light" wire:click="keluarkanAnggota('{{$item->anggota_rombel->anggota_rombel_id}}', '{{$item->anggota_rombel->rombongan_belajar_id}}')">Keluarkan</button>
                                </td>
                            </tr>
                        @endforeach
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
