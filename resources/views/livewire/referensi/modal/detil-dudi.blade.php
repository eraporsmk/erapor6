<div>
    <div wire:ignore.self class="modal fade" id="detilModal" tabindex="-1" aria-labelledby="detilModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detilModalLabel">Informasi Detil {{$nama}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($dudi)
                    <table class="table">
                        <tr>
                            <td>Nama</td>
                            <td>{{$dudi->nama}}</td>
                        </tr>
                        <tr>
                            <td>Bidang Usaha</td>
                            <td>{{$dudi->nama_bidang_usaha}}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>{{$dudi->alamat_jalan}}</td>
                        </tr>
                    </table>
                    <h4 class="card-title">MoU</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">Nomor MoU</th>
                                <th class="text-center">Judul MoU</th>
                                <th class="text-center">Periode Kerja Sama</th>
                                <th class="text-center">Narahubung</th>
                                <th class="text-center">Telp. Narahubung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($dudi && $dudi->mou->count())
                            @foreach ($dudi->mou as $mou)
                                <tr>
                                    <td>{{$mou->nomor_mou}}</td>
                                    <td>{{$mou->judul_mou}}</td>
                                    <td>{{$mou->tanggal_mulai}} s/d {{$mou->tanggal_selesai}}</td>
                                    <td>{{$mou->contact_person}}</td>
                                    <td>{{$mou->telp_cp}}</td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <h4 class="card-title">Aktifitas Peserta Didik</h4>
                    @if($dudi->mou->count())
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Nama Kegiatan</th>
                                    <th class="text-center">SK Tugas</th>
                                    <th class="text-center">Guru Pembimbing</th>
                                    <th class="text-center">Jml PD</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dudi->mou as $mou)
                                    @foreach ($mou->akt_pd as $akt_pd)
                                    <tr>
                                        <td>{{$akt_pd->judul_akt_pd}}</td>
                                        <td>{{$akt_pd->sk_tugas}}</td>
                                        <td>
                                            <ul>
                                            @foreach ($akt_pd->bimbing_pd as $bimbing_pd)
                                            <li>{{$bimbing_pd->guru->nama}}</li>    
                                            @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-center">{{$akt_pd->anggota_akt_pd_count}}</td>
                                        <td class="text-center"><button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#anggotaAktPd" wire:click="aktPdID('{{$akt_pd->akt_pd_id}}')">Detil Anggota</button></td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
