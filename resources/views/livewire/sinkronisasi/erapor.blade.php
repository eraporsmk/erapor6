<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-header py-1 px-1">
                <h2><i class="fa-solid fa-school-flag"></i> Identitas Sekolah</h2>
              </div>
            <div class="card-body"></div>
            <hr>
            <div class="card-header py-0 pb-1 border-bottom">
                <h2><i class="fa-solid fa-signal"></i> Status Koneksi</h2>
                <button class="btn btn-success">TERHUBUNG</button>
            </div>
            <div class="card-body text-center py-1">
                <p>Pengiriman data dilakukan terakhir <strong>16 Oktober 2019</strong></p>
                <button class="btn btn-success btn-lg"><i class="fa-solid fa-arrows-rotate"></i> SINKRONISASI</button>
            </div>
            <div class="card-header py-0 pb-1 border-bottom">
                <h2><i class="fa-solid fa-list-check"></i> Data yang Mengalami Perubahan</h2>
            </div>
            <div class="card-body py-1">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="80%">Nama Tabel</th>
                            <th width="15%">Jml Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>2</td>
                            <td class="text-center">3</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
