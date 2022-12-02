<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            @if($merdeka)
            <div class="card-body">
                <div class="alert alert-danger" role="alert">
                    <div class="alert-body text-center">
                        <h2>Fitur Ditutup</h2>
                        <p>Fitur Catatan Sikap hanya untuk <strong>Kurikulum 2013</strong></p>
                    </div>
                </div>
            </div>
            @else
            <form wire:ignore.self wire:submit.prevent="store">
                <div class="card-body">
                    @role(['waka', 'tu'], session('semester_id'))
                    @include('livewire.formulir-waka')
                    @endrole
                    @if($show)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Nama Peserta Didik</th>
                                <th class="text-center">Nilai Sikap dari Guru</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_siswa as $item)
                            <tr>
                                <td>{{$item->nama}}</td>
                                <td>
                                    @if($item->anggota_rombel->nilai_budaya_kerja_guru->count())
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nama Guru</th>
                                                <th class="text-center">Dimensi Sikap</th>
                                                <th class="text-center">Elemen Sikap</th>
                                                <th class="text-center">Catatan Sikap</th>
                                                <th class="text-center">Opsi Sikap</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item->anggota_rombel->nilai_budaya_kerja_guru as $nilai)
                                                <tr>
                                                    <td class="align-top">{{$nilai->guru->nama_lengkap}}</td>
                                                    <td class="align-top">{{$nilai->budaya_kerja->aspek}}</td>
                                                    <td class="align-top">{{$nilai->elemen_budaya_kerja->elemen}}</td>
                                                    <td class="align-top">{{$nilai->deskripsi}}</td>
                                                    <td class="align-top text-center">{{($nilai->opsi_id == 1) ? 'Positif' : 'Negatif'}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <span class="text-center">Tidak ada catatan sikap dari guru</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h2 class="text-center">Catatan Sikap Wali Kelas</h2>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="30%">Dimensi Sikap</th>
                                                <th class="text-center" width="70%">Catatan Sikap</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($budaya_kerja as $budaya)
                                            <tr>
                                                <td class="align-top">{{$budaya->aspek}}</td>
                                                <td class="align-top">
                                                    @if($form)
                                                    <textarea wire:model.defer="uraian_sikap.{{$item->anggota_rombel->anggota_rombel_id}}.{{$budaya->budaya_kerja_id}}" class="form-control"></textarea>
                                                    @else
                                                    <textarea wire:model.defer="uraian_sikap.{{$item->anggota_rombel->anggota_rombel_id}}.{{$budaya->budaya_kerja_id}}" class="form-control" disabled></textarea>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="3">Tidak ada untuk ditampilkan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @endif
                </div>
                <div class="card-footer{{($form) ? '' : ' d-none'}}">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            @endif
        </div>
    </div>
    @include('components.loader')
</div>
