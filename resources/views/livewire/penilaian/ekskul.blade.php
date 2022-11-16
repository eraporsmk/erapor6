<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:ignore.self wire:submit.prevent="store">
                <div class="card-body">
                    <div class="row mb-2">
                        <label for="semester_id" class="col-sm-3 col-form-label">Tahun Ajaran</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" readonly wire:model="semester_id">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Ekstrakurikuler</label>
                        <div class="col-sm-9">
                            <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" wire:change="changeEkskul">
                                <option value="">== Pilih Nama Ekstrakurikuler ==</option>
                                @foreach ($collection as $item)
                                <option value="{{$item->rombongan_belajar_id}}">{{$item->nama_ekskul}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2{{($show) ? '' : ' d-none'}}">
                        <label for="rombongan_belajar_id_reguler" class="col-sm-3 col-form-label">Filter Kelas</label>
                        <div class="col-sm-9">
                            <select id="rombongan_belajar_id_reguler" class="form-select" wire:model="rombongan_belajar_id_reguler" wire:change="changeReguler">
                                <option value="">== Semua Kelas ==</option>
                                @foreach ($data_rombel as $rombel)
                                <option value="{{$rombel->rombongan_belajar_id}}">{{$rombel->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive{{($show) ? '' : ' d-none'}}">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" width="20%">Nama Siswa</th>
                                    <th class="text-center" width="10%">Kelas</th>
                                    <th class="text-center" width="20%">Predikat</th>
                                    <th class="text-center" width="50%">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_siswa as $siswa)
                                <tr>
                                    <td>{{$siswa->nama}}</td>
                                    <td>
                                        {{($siswa->kelas) ? $siswa->kelas->nama : '-'}}
                                    </td>
                                    <td>
                                        <select wire:model.lazy="nilai_ekskul.{{$siswa->anggota_ekskul->anggota_rombel_id}}" class="form-select" wire:change="changeNilai('{{$siswa->anggota_ekskul->anggota_rombel_id}}')">
                                            <option value="">== Pilih Predikat ==</option>
                                            <option value="1">Sangat Baik</option>
                                            <option value="2">Baik</option>
                                            <option value="3">Cukup</option>
                                            <option value="4">Kurang</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="deskripsi_ekskul" wire:model="deskripsi_ekskul.{{$siswa->anggota_ekskul->anggota_rombel_id}}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer{{($show) ? '' : ' d-none'}}">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();

    });
</script>

@endpush
