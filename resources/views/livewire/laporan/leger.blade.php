<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @if($rombongan_belajar_id)
                    <div class="row">
                        <div class="col-4">
                            <a href="{{route('unduhan.unduh-leger-kd', ['rombongan_belajar_id' => $rombongan_belajar_id])}}" target="_blank" title="Unduh Leger KD">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h2 class="text-white">Leger KD</h2>
                                        <p class="card-text">Menampilkan Nilai Otentik per Kompetensi Dasar.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{route('unduhan.unduh-leger-nilai-akhir', ['rombongan_belajar_id' => $rombongan_belajar_id])}}" target="_blank" title="Unduh Leger Nilai Akhir">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h2 class="text-white">Leger Nilai Akhir</h2>
                                        <p class="card-text">Menampilkan Nilai Akhir per Kompetensi.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{route('unduhan.unduh-leger-nilai-rapor', ['rombongan_belajar_id' => $rombongan_belajar_id])}}" target="_blank" title="Unduh Leger Nilai rapor">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h2 class="text-white">Leger Nilai rapor</h2>
                                        <p class="card-text">Menampilkan Nilai Rapor</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @else
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h2 class="text-white">Akses Ditutup</h2>
                        <p class="card-text">Rombongan Belajar tidak ditemukan!</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
