<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        @if ($show)
            <div class="card text-white bg-dark text-center">
                <div class="card-body fs-4 p-1" id="syncText">{{ $status }}</div>
            </div>
        @endif
        <div class="row match-height">
            <div class="col-lg-8">
                <div class="card card-congratulation-medal">
                    <div class="card-body p-0">
                        <table class="table table-bordered">
                            <tr>
                                <td rowspan="4" width="10%" class="table-light">
                                    <img src="/images/logo.png" alt="Logo" style="max-width: 100px">
                                </td>
                                <td width="30%" class="table-light fw-bold">NPSN Sekolah</td>
                                <td width="60%">{{ $sekolah->npsn }}</td>
                            </tr>
                            <tr>
                                <td class="table-light">Nama Sekolah</td>
                                <td>{{ $sekolah->nama }}</td>
                            </tr>
                            <tr>
                                <td class="table-light">Alamat Sekolah</td>
                                <td>{{ $sekolah->alamat }}</td>
                            </tr>
                            <tr>
                                <td class="table-light">Desa Kelurahan Sekolah</td>
                                <td>{{ $sekolah->desa_kelurahan }}</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <p>Pengiriman data terakhir dilakukan pada <br>
                            @if($last_sync)
                            {{$last_sync->updated_at->translatedFormat('d F Y H:i:s')}}
                            @else
                            <strong>10 Desember 2022 09:20</strong>
                            @endif
                        </p>
                        @if ($show)
                            <button class="btn btn-lg btn-success" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span class="visually-hidden">Loading...</span>
                            </button>
                        @else
                            <button class="btn btn-success btn-lg" wire:click="mulaiKirim"><i
                                    class="fa-solid fa-cloud-arrow-up"></i> KIRIM DATA</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card text-white bg-secondary text-center">
            <div class="card-body fs-4 p-1">DATA YANG MENGALAMI PERUBAHAN</div>
        </div>
        <div class="card">
            <div class="card-body py-1">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="80%" class="text-center">Data</th>
                            <th width="15%" class="text-center">Jml Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $jml = 0;
                        ?>
                        @foreach ($table_sync as $sync)
                            @if($sync['count'])
                            <tr>
                                <td class="text-center">{{$no}}</td>
                                <td>{{$sync['data']}}</td>
                                <td class="text-center">{{$sync['count']}}</td>
                            </tr>
                            <?php
                            $jml += $sync['count'];
                            $no++;
                            ?>
                            @endif 
                        @endforeach
                    </tbody>
                    <tfoot>
                        @if($jml)
                        <tr>
                            <th class="text-end" colspan="2">Jumlah</th>
                            <th class="text-center">{{$jml}}</th>
                        </tr> 
                        @else
                        <tr>
                            <td class="text-center" colspan="3">Tidak ada untuk ditampilkan</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        var myInterval;
        function myTimer() {
            $.get("/api/hitung/{{ $sekolah->sekolah_id }}", function(data, status) {
                if (data.output) {
                    if (data.output.jumlah) {
                        $('#syncText').text(data.output.table + ' (' + data.output.inserted + '/' + data.output
                            .jumlah + ')');
                    } else {
                        $('#syncText').text(data.output.table);
                    }
                }
            });
        }
        Livewire.on('prosesSync', function() {
            myInterval = setInterval(myTimer, 500);
        })
        Livewire.on('finishSync', function(e) {
            clearInterval(myInterval);
        })
    </script>
@endpush
