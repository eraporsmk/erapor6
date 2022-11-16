<div>
    @include('panels.breadcrumb')
    <div class="row match-height">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-info p-50 mb-1">
                        <div class="avatar-content">
                            <i class="fa-solid fa-user-graduate font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bolder">{{$sekolah->ptk_count}}</h2>
                    <p class="card-text">GTK</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-warning p-50 mb-1">
                        <div class="avatar-content">
                            <i class="fa-solid fa-children font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bolder">{{$sekolah->pd_aktif_count}}</h2>
                    <p class="card-text">Peserta Didik</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-danger p-50 mb-1">
                        <div class="avatar-content">
                            <i class="fa-solid fa-spell-check font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bolder">
                        <a data-bs-toggle="tooltip" data-bs-title="Jumlah Tujuan Pembelajaran yang telah di input oleh PTK">
                            {{$tp}}
                        </a>
                    </h2>
                    <p class="card-text">Tujuan Pembelajaran</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-primary p-50 mb-1">
                        <div class="avatar-content">
                            <i class="fa-solid fa-list-check font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bolder">
                        <a data-bs-toggle="tooltip" data-bs-title="Jumlah Mata Pelajaran yang telah dinilai">
                            {{$sekolah->nilai_akhir_count}}
                        </a>
                    </h2>
                    <p class="card-text">Nilai Akhir</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-success p-50 mb-1">
                        <div class="avatar-content">
                            <i class="fa-solid fa-list-check font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bolder">
                        <a data-bs-toggle="tooltip" data-bs-title="Jumlah Mata Pelajaran yang telah di input Deskripsi Capaian Pembelajaran">
                        {{$sekolah->cp_count}}
                        </a>
                    </h2>
                    <p class="card-text">Capaian Kompetensi</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar bg-light-danger p-50 mb-1">
                        <div class="avatar-content">
                            <i class="fa-solid fa-list-check font-medium-5"></i>
                        </div>
                    </div>
                    <h2 class="fw-bolder">
                        <a data-bs-toggle="tooltip" data-bs-title="Jumlah Peserta Didik yang telah dinilai P5">
                        {{$sekolah->nilai_keterampilan_count}}
                        </a>
                    </h2>
                    <p class="card-text">Projek Penguatan Profil Pelajar Pancasila</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row match-height">
        <div class="col-xl-7 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Identitas Sekolah</h4>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li>
                                <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="table-responsive mx-1">
                        <table class="table">
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%">: {{$sekolah->nama}}</td>
                            </tr>
                            <tr>
                                <td>NPSN</td>
                                <td>: {{$sekolah->npsn}}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: {{$sekolah->alamat}}</td>
                            </tr>
                            <tr>
                                <td>Kodepos</td>
                                <td>: {{$sekolah->kode_pos}}</td>
                            </tr>
                            <tr>
                                <td>Desa/Kelurahan</td>
                                <td>: {{$sekolah->desa_kelurahan}}</td>
                            </tr>
                            <tr>
                                <td>Kecamatan</td>
                                <td>: {{$sekolah->kecamatan}}</td>
                            </tr>
                            <tr>
                                <td>Kabupaten/Kota</td>
                                <td>: {{$sekolah->kabupaten}}</td>
                            </tr>
                            <tr>
                                <td>Provinsi</td>
                                <td>: {{$sekolah->provinsi}}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: {{$sekolah->email}}</td>
                            </tr>
                            <tr>
                                <td>Website</td>
                                <td>: {{$sekolah->website}}</td>
                            </tr>
                            <tr>
                                <td>Kepala Sekolah</td>
                                <td>: {{($sekolah->kepala_sekolah) ? $sekolah->kepala_sekolah->nama_lengkap : '-'}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Aplikasi</h4>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li>
                                <a data-action="collapse"><i data-feather="chevron-down"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="table-responsive mx-1">
                        <table class="table">
                            <tr>
                                <td width="40%">Nama Aplikasi</td>
                                <td width="60%"> {{config('app.name')}}</td>
                            </tr>
                            <tr>
                                <td>Versi Aplikasi</td>
                                <td> {{config('global.app_version')}}</td>
                            </tr>
                            <tr>
                                <td>Versi Database</td>
                                <td> {{config('global.db_version')}}</td>
                            </tr>
                            <tr>
                                <td>Status Penilaian</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" wire:model="status_penilaian" wire:change="gantiStatus">
                                      </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Group Diskusi</td>
                                <td>: 
                                    <a href="https://www.facebook.com/groups/2003597939918600/" target="_blank"><i class="fa-brands fa-facebook"></i> FB Group</a> 
                                    <a href="http://t.me/eRaporSMK" target="_blank"><i class="fa-brands fa-telegram"></i> Telegram</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Tim Helpdesk</td>
                                <td>
                                    <div class="btn-group-vertical">
                                        <a target="_blank" href="https://api.whatsapp.com/send?phone=628156441864&text=NPSN:{{$sekolah->npsn}}"><i class="fa-brands fa-whatsapp"></i> Wahyudin [08156441864]</a>
                                        <a target="_blank" href="https://api.whatsapp.com/send?phone=6281229997730&amp;text=NPSN:{{$sekolah->npsn}}"><i class="fa-brands fa-whatsapp"></i> Ahmad Aripin [081229997730]</a>
                                        <a target="_blank" href="https://api.whatsapp.com/send?phone=6282113057512&amp;text=NPSN:{{$sekolah->npsn}}"><i class="fa-brands fa-whatsapp"></i> Iman [082113057512]</a>
                                        <a target="_blank" href="https://api.whatsapp.com/send?phone=6282174508706&amp;text=NPSN:{{$sekolah->npsn}}"><i class="fa-brands fa-whatsapp"></i> Ikhsan [082174508706]</a>
                                        <a target="_blank" href="https://api.whatsapp.com/send?phone=6282134924288&amp;text=NPSN:{{$sekolah->npsn}}"><i class="fa-brands fa-whatsapp"></i> Toni [082134924288]</a>
                                        <a target="_blank" href="https://api.whatsapp.com/send?phone=6285624669298&amp;text=NPSN:{{$sekolah->npsn}}"><i class="fa-brands fa-whatsapp"></i> Deetha [085624669298]</a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p>Aplikasi e-Rapor SMK ini dibuat dan dikembangkan oleh Direktorat Sekolah Menengah Kejuruan<br>
        Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi Republik Indonesia</p>
    @include('components.loader')
</div>
