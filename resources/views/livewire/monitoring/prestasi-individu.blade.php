<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <label for="semester_id" class="col-sm-3 col-form-label">Tahun Pelajaran</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" readonly wire:model="semester_id">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="tingkat" class="col-sm-3 col-form-label">Tingkat Kelas</label>
                    <div class="col-sm-9" wire:ignore>
                        <select id="tingkat" class="form-select" wire:model="tingkat" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Tingkat Kelas ==" data-search-off="true">
                            <option value="">== Pilih Tingkat Kelas ==</option>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                            <option value="13">Kelas 13</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2" wire:ignore>
                    <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Rombongan Belajar</label>
                    <div class="col-sm-9">
                        <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Rombongan Belajar ==">
                            <option value="">== Pilih Rombongan Belajar ==</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2" wire:ignore>
                    <label for="pembelajaran_id" class="col-sm-3 col-form-label">Mata Pelajaran</label>
                    <div class="col-sm-9">
                        <select id="pembelajaran_id" class="form-select" wire:model="pembelajaran_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Mata Pelajaran ==">
                            <option value="">== Pilih Mata Pelajaran ==</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2" wire:ignore>
                    <label for="siswa" class="col-sm-3 col-form-label">Nama Peserta Didik</label>
                    <div class="col-sm-9">
                        <select id="siswa" class="form-select" wire:model="siswa" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Peserta Didik ==">
                            <option value="">== Pilih Peserta Didik ==</option>
                        </select>
                    </div>
                </div>
                @if($show)
                <h2>Sebaran Hasil Penilaian</h2>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered {{table_striped()}}">
                                    <tr>
                                        <th width="40%">Kompetensi</th>
                                        <th class="text-center" width="5%">:</th>
                                        <th width="55%">Pengetahuan</th>
                                    </tr>
                                    <tr>
                                        <th>SKM</th>
                                        <th class="text-center">:</th>
                                        <th>{{$skm}}</th>
                                    </tr>
                                    <tr>
                                        <th>Nilai rata-rata</th>
                                        <th class="text-center">:</th>
                                        <th>{{number_format($rerata_pengetahuan, 0)}}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div id="chart_pengetahuan"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered {{table_striped()}}">
                                    <tr>
                                        <th width="40%">Kompetensi</th>
                                        <th class="text-center" width="5%">:</th>
                                        <th width="55%">Keterampilan</th>
                                    </tr>
                                    <tr>
                                        <th>SKM</th>
                                        <th class="text-center">:</th>
                                        <th>{{$skm}}</th>
                                    </tr>
                                    <tr>
                                        <th>Nilai rata-rata</th>
                                        <th class="text-center">:</th>
                                        <th>{{number_format($rerata_keterampilan, 0)}}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div id="chart_keterampilan"></div>
                        </div>
                    </div>
                </div>
                <div id="chart" class="mt-2"></div>
                @endif
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('data_rombongan_belajar', event => {
        $('#rombongan_belajar_id').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $('#pembelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $('#siswa').html('<option value="">== Pilih Peserta Didik ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_pembelajaran', event => {
        $('#pembelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $('#siswa').html('<option value="">== Pilih Peserta Didik ==</option>')
        $.each(event.detail.data_pembelajaran, function (i, item) {
            $('#pembelajaran_id').append($('<option>', { 
                value: item.pembelajaran_id,
                text : item.nama_mata_pelajaran
            }));
        });
    })
    window.addEventListener('data_siswa', event => {
        $.each(event.detail.data_siswa, function (i, item) {
            $('#siswa').append($('<option>', { 
                value: item.anggota_rombel.anggota_rombel_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('chart', event => {
        var options = {
          series: [{
          name: 'Nilai Peserta Didik',
          type: 'column',
          data: event.detail.chart.nilai_kd_pengetahuan
        }, {
          name: 'SKM',
          type: 'line',
          //data: [70, 70]
          data: event.detail.chart.skm_p
        }],
          chart: {
          height: 350,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Hasil Analisis Capaian Kompetensi'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels: event.detail.chart.id_kompetensi_pengetahuan,
        yaxis: {
            min: 0,
            max: 100,
        },
        xaxis: {
          type: 'category'
        },
        };

        var chart_pengetahuan = new ApexCharts(document.querySelector("#chart_pengetahuan"), options);
        chart_pengetahuan.render();
        var options = {
          series: [{
          name: 'Nilai Peserta Didik',
          type: 'column',
          data: event.detail.chart.nilai_kd_keterampilan
        }, {
          name: 'SKM',
          type: 'line',
          //data: [70, 70]
          data: event.detail.chart.skm_k
        }],
          chart: {
          height: 350,
          type: 'line',
        },
        stroke: {
          width: [0, 4]
        },
        title: {
          text: 'Hasil Analisis Capaian Kompetensi'
        },
        dataLabels: {
          enabled: true,
          enabledOnSeries: [1]
        },
        labels: event.detail.chart.id_kompetensi_keterampilan,
        yaxis: {
            min: 0,
            max: 100,
        },
        xaxis: {
          type: 'category'
        },
        };

        var chart_keterampilan = new ApexCharts(document.querySelector("#chart_keterampilan"), options);
        chart_keterampilan.render();
    })
</script>
@endpush
