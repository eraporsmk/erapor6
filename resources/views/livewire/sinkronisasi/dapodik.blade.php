<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            @if($online)
                <div class="card-body">
                    <!--div wire:loading.remove>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg btn-block" wire:click="clickSync">Proses Sinkronisasi</button>    
                        </div>
                    </div-->
                    <div wire:loading.grid>
                        <div class="alert bg-dark" role="alert">
                            <div id="syncText" class="alert-body text-center text-white">
                                {{$syncText}}
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table px-1">
                    <thead>
                        <tr>
                            <th class="text-center">Data</th>
                            <th class="text-center">Jml Data Dapodik</th>
                            <th class="text-center">Jml Data e-Rapor</th>
                            <th class="text-center">Jml Data Tersinkronisasi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_sinkron as $sinkron)
                        <tr>
                            <td class="ps-2">{{$sinkron['nama']}}</td>
                            <td class="text-center">{{$sinkron['dapodik']}}</td>
                            <td class="text-center">{{$sinkron['erapor']}}</td>
                            <td class="text-center">{{$sinkron['sinkron']}}</td>
                            <td class="text-center">
                                <div class="d-grid">
                                    @if($sinkron['erapor'])
                                        @if($sinkron['dapodik'] > $sinkron['erapor'])
                                        <span class="badge d-block bg-warning">
                                            <span>Kurang</span>
                                        </span>
                                        @else
                                        <span class="badge d-block bg-success">
                                            <span>Lengkap</span>
                                        </span>
                                        @endif
                                    @else
                                        <span class="badge d-block bg-danger">
                                            <span>Belum</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-grid">
                                    <div wire:loading>
                                        <button class="btn btn-sm btn-success" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            <span class="visually-hidden">Loading...</span>
                                        </button>
                                    </div>
                                    <div wire:loading.remove>
                                        <button class="btn btn-sm btn-success" wire:click="syncSatuan('{{$sinkron['server']}}', '{{$sinkron['aksi']}}')">Sinkronisasi</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
            <div class="card-body">
                <div class="alert alert-danger" role="alert">
                    <div class="alert-body text-center">
                        Tidak terhubung ke server Direktorat SMK
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            var myInterval;
            function myTimer() {
                $.get("/api/hitung/{{$sekolah_id}}", function(data, status){
                    if(data.output){
                        if(data.output.jumlah){
                            $('#syncText').text(data.output.table+' ('+data.output.inserted+'/'+data.output.jumlah+')');
                        } else {
                            $('#syncText').text(data.output.table);
                        }
                    }
                });
            }
            Livewire.on('delaySync', function(){
                console.log('delaySync');
                //myInterval = setInterval(myTimer, 500);
            })
            Livewire.on('prosesSync', function(){
                console.log('prosesSync');
                myInterval = setInterval(myTimer, 500);
            })
            Livewire.on('finishSync', function(e){
                console.log('finishSync');
                clearInterval(myInterval);
            })
        })
    </script>
@endpush
