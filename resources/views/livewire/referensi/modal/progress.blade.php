<div>
    <div wire:ignore.self class="modal fade" id="progressBar" tabindex="-1" aria-labelledby="progressBarLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 class="text-center">Data Peserta Didik hasil sinkronisasi</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Diterima Dikelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($result as $nama => $kelas)
                            <tr>
                                <td>{{$nama}}</td>
                                <td>{{$kelas}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="2">Tidak ada data untuk ditampilkan</td>
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
