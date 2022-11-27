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
                        <select id="tingkat" class="form-select" wire:model="tingkat" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Tingkat Kelas ==" data-search-off="true" wire:change="changeTingkat">
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
                        <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Rombongan Belajar ==" wire:change="changeRombel">
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
                    <label for="kompetensi_id" class="col-sm-3 col-form-label">Kompetensi Penilaian</label>
                    <div class="col-sm-9">
                        <select id="kompetensi_id" class="form-select" wire:model="kompetensi_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Kompetensi Penilaian ==">
                            <option value="">== Pilih Kompetensi Penilaian ==</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2" wire:ignore>
                    <label for="kompetensi_dasar_id" class="col-sm-3 col-form-label">Kompetensi Dasar</label>
                    <div class="col-sm-9">
                        <select id="kompetensi_dasar_id" class="form-select" wire:model="kompetensi_dasar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Kompetensi Penilaian ==">
                            <option value="">== Pilih Kompetensi Dasar ==</option>
                        </select>
                    </div>
                </div>
                @if($show)
                <table class="table table-bordered {{table_striped()}}">
                    <tr>
                        <th width="20%">Rombongan Belajar</th>
                        <th class="text-center" width="5%">:</th>
                        <th width="75%">{{$nama_rombel}}</th>
                    </tr>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th class="text-center">:</th>
                        <th>{{$nama_mapel}}</th>
                    </tr>
                    <tr>
                        <th>SKM</th>
                        <th class="text-center">:</th>
                        <th>{{$kkm}}</th>
                    </tr>
                    <tr>
                        <th>Kompetensi Dasar</th>
                        <th class="text-center">:</th>
                        <th>{{$kompetensi_dasar}}</th>
                    </tr>
                </table>
                <div id="chart" class="mt-2"></div>
                @endif
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('data_rombongan_belajar', event => {
        $('#rombongan_belajar_id').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $('#pembelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $('#kompetensi_id').html('<option value="">== Pilih Kompetensi Penilaian ==</option>')
        $('#kompetensi_dasar_id').html('<option value="">== Pilih Kompetensi Dasar ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_pembelajaran', event => {
        $('#pembelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $('#kompetensi_id').html('<option value="">== Pilih Kompetensi Penilaian ==</option>')
        $('#kompetensi_dasar_id').html('<option value="">== Pilih Kompetensi Dasar ==</option>')
        $.each(event.detail.data_pembelajaran, function (i, item) {
            $('#pembelajaran_id').append($('<option>', { 
                value: item.pembelajaran_id,
                text : item.nama_mata_pelajaran
            }));
        });
    })
    window.addEventListener('data_kompetensi', event => {
        console.log(event.detail.data_kompetensi);
        $('#kompetensi_id').html('<option value="">== Pilih Kompetensi Penilaian ==</option>')
        $('#kompetensi_dasar_id').html('<option value="">== Pilih Kompetensi Dasar ==</option>')
        $.each(event.detail.data_kompetensi, function (i, item) {
            $('#kompetensi_id').append($('<option>', { 
                value: item.id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_kd', event => {
        $('#kompetensi_dasar_id').html('<option value="">== Pilih Kompetensi Dasar ==</option>')
        $.each(event.detail.data_kd, function (i, item) {
            $('#kompetensi_dasar_id').append($('<option>', { 
                value: item.kompetensi_dasar_id,
                text : item.id_kompetensi
            }));
        });
    })
    window.addEventListener('chart', event => {
        console.log(event.detail.chart.skm);
        var options = {
          series: [{
          name: 'Nilai Peserta Didik',
          type: 'column',
          data: event.detail.chart.nilai_kd
        }, {
          name: 'SKM',
          type: 'line',
          //data: [70, 70]
          data: event.detail.chart.skm
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
        labels: event.detail.chart.data_siswa,
        yaxis: {
            min: 0,
            max: 100,
        },
        xaxis: {
          type: 'category'
        },
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    })
</script>
@endpush
