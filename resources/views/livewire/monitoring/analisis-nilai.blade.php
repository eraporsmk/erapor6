<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @include('livewire.formulir-rencana-guru')
                @if($show)
                <h3>Sebaran Hasil Penilaian per Rencana Penilaian</h3>
                <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-bordered {{table_striped()}}">
                            <tbody>
                                <tr>
                                    <td width="40%">Rombongan Belajar</td>
                                    <td class="text-center" width="5%">:</td>
                                    <td width="55%">{{$nama_rombel}}</td>
                                </tr>
                                <tr>
                                    <td>Mata Pelajaran</td>
                                    <td class="text-center">:</td>
                                    <td>{{$nama_mapel}}</td>
                                </tr>
                                <tr>
                                    <td>Penilaian</td>
                                    <td class="text-center">:</td>
                                    <td>{{$nama_rencana}}</td>
                                </tr>
                                <tr>
                                    <td>SKM</td>
                                    <td class="text-center">:</td>
                                    <td>{{$kkm}}</td>
                                </tr>
                                <tr>
                                    <td>Bobot Penilaian</td>
                                    <td class="text-center">:</td>
                                    <td>{{$bobot}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div id="chartdiv"></div>
                    </div>
                    <div class="col-sm-3">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td width="50%" class="text-center">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" href="javascript:void(0)" title="95-100">A+</a>
                                </td>
                                <td width="50%" class="text-center">
                                    {!! sebaran_tooltip($nilai_value, predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'A'), predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'A+'),'left') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" href="javascript:void(0)" title="90-94">A</a>
                                </td>
                                <td class="text-center">
                                    {!! sebaran_tooltip($nilai_value, predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'A-'), predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'A'),'left') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" href="javascript:void(0)" title="90-94">A-</a>
                                </td>
                                <td class="text-center">
                                    {!! sebaran_tooltip($nilai_value, predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'B'), predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'A-'),'left') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" href="javascript:void(0)" title="80-84">B+</a>
                                </td>
                                <td class="text-center">
                                    {!! sebaran_tooltip($nilai_value, predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'B'), predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'B+'),'left') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" href="javascript:void(0)" title="75-79">B</a>
                                </td>
                                <td class="text-center">
                                    {!! sebaran_tooltip($nilai_value, predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'B-'), predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'B'),'left') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" href="javascript:void(0)" title="70-74">B-</a>
                                </td>
                                <td class="text-center">
                                    {!! sebaran_tooltip($nilai_value, predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'C'), predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'B-'),'left') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" href="javascript:void(0)" title="60-69">C</a>
                                </td>
                                <td class="text-center">
                                    {!! sebaran_tooltip($nilai_value, get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm), predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'C'),'left') !!}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <a data-bs-toggle="tooltip" data-bs-placement="left" data-bs-html="true" href="javascript:void(0)" title="0-59">D</a>
                                </td>
                                <td class="text-center">
                                    {!! sebaran_tooltip($nilai_value,0, predikat( get_kkm($rencana_penilaian->pembelajaran->kelompok_id, $rencana_penilaian->pembelajaran->kkm),'D'),'left') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('chart', event => {
        console.log(event.detail.chart);
        $('[data-bs-toggle="tooltip"]').tooltip()
        var options = {
            series: [{
            name: 'Jumlah PD',
            data: event.detail.chart
            }],
            chart: {
            height: 350,
            type: 'bar',
            },
            plotOptions: {
            bar: {
                borderRadius: 10,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
            }
            },
            /*dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val + " Peserta Didik";
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#304758"]
            }
            },*/
            
            xaxis: {
                categories: ["100-95", "89-85", "84-80", "79-75", "74-70", "69-65", "64-60", "59-55", "54-50", "49-0"],
                position: 'top',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false,
                    formatter: function (val) {
                    return "Rentang Nilai " + val;
                    }
                },
                crosshairs: {
                    fill: {
                    type: 'gradient',
                    gradient: {
                        colorFrom: '#D8E3F0',
                        colorTo: '#BED1E6',
                        stops: [0, 100],
                        opacityFrom: 0.4,
                        opacityTo: 0.5,
                    }
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    formatter: function (val) {
                    return val;
                    }
                }
            },
            title: {
                text: 'Sebaran Peserta Didik',
                floating: true,
                offsetY: 330,
                align: 'center',
                style: {
                    color: '#444'
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#chartdiv"), options);
        chart.render();
    })
</script>
@endpush
