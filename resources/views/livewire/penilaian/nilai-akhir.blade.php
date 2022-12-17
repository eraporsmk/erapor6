<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:ignore.self wire:submit.prevent="store">
                <div class="card-body">
                    @include('livewire.formulir-umum')
                    <div class="row mb-2 {{($show && count($data_tp)) ? '' : 'd-none'}}">
                        <div class="col-6">
                            <input class="form-control" type="file" wire:model="template_excel">
                            @error('template_excel') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6 d-grid">
                            <a class="btn btn-primary" href="{{route('unduhan.template-nilai-akhir', ['pembelajaran_id' => $pembelajaran_id])}}">Unduh Template Nilai Akhir</a>
                        </div>
                    </div>
                    <div class="row mb-2 {{($show) ? '' : 'd-none'}}" wire:loading.remove wire:target="changePembelajaran">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" rowspan="2">Nama Peserta Didik</th>
                                        <th class="text-center align-middle" rowspan="2">Nilai Akhir</th>
                                        <th class="text-center align-middle" colspan="3">Capaian Kompetensi</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center align-middle">Dicapai</th>
                                        <th class="text-center align-middle">Belum dicapai</th>
                                        <th class="text-center align-middle">Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    ?>
                                    @if(count($data_tp))
                                    @foreach ($data_siswa as $siswa)
                                    <tr @if($no % 2 == 0) class="table-secondary" @endif>
                                        <td rowspan="{{count($data_tp) + 1}}">
                                            {{$siswa->nama}}
                                        </td>
                                        <td class="text-center" rowspan="{{count($data_tp) + 1}}">
                                            <input type="number" class="form-control @error('nilai.'.$siswa->anggota_rombel->anggota_rombel_id) is-invalid @enderror" wire:model.defer="nilai.{{$siswa->anggota_rombel->anggota_rombel_id}}">
                                            @error('nilai.'.$siswa->anggota_rombel->anggota_rombel_id) {{$message}} @enderror
                                        </td>
                                    </tr>
                                    @foreach ($data_tp as $tp)
                                    <tr @if($no % 2 == 0) class="table-secondary" @endif>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" value="{{$tp->tp_id}}" id="tp_dicapai-{{$tp->tp_id}}" wire:model="tp_dicapai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$tp->tp_id}}"
                                                @if(isset($tp_belum_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id]) && $tp_belum_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id])
                                                disabled
                                                @endif
                                                >
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" value="{{$tp->tp_id}}" id="tp_belum_dicapai-{{$tp->tp_id}}" wire:model="tp_belum_dicapai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$tp->tp_id}}"
                                                @if(isset($tp_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id]) && $tp_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id])
                                                disabled
                                                @endif
                                                >
                                            </div>
                                        </td>
                                        <td>
                                            {{$tp->deskripsi}}
                                        </td>
                                    </tr>
                                    @endforeach
                                        {{--
                                        <td>
                                            @foreach ($data_tp as $tp)
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="{{$tp->tp_id}}" id="tp_belum_dicapai-{{$tp->tp_id}}" wire:model="tp_belum_dicapai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$tp->tp_id}}"
                                                @if(isset($tp_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id]) && $tp_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id])
                                                disabled
                                                @endif
                                                >
                                                <label class="form-check-label" for="tp_belum_dicapai-{{$tp->tp_id}}">
                                                    {{$tp->deskripsi}}
                                                </label>
                                            </div>
                                            @endforeach
                                        </td>
                                        --}}
                                    <?php $no++; ?>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5">
                                            <div class="alert alert-danger" role="alert">
                                                <div class="alert-body text-center">
                                                    <h2>Tidak ditemukan data Tujuan Pembelajaran</h2>
                                                    <p>Silahkan tambah data Tujuan Pembelajaran terlebih dahulu <a href="{{route('referensi.tujuan-pembelajaran.tambah')}}">disini</a></p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                {{--
                                <tbody>
                                    @if(count($data_tp))
                                    @foreach ($data_siswa as $siswa)
                                    <tr>
                                        <td>
                                            {{$siswa->nama}}
                                        </td>
                                        <td class="text-center">
                                            <input type="number" class="form-control @error('nilai.'.$siswa->anggota_rombel->anggota_rombel_id) is-invalid @enderror" wire:model.defer="nilai.{{$siswa->anggota_rombel->anggota_rombel_id}}">
                                            @error('nilai.'.$siswa->anggota_rombel->anggota_rombel_id) {{$message}} @enderror
                                        </td>
                                        <td>
                                            @foreach ($data_tp as $tp)
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="{{$tp->tp_id}}" id="tp_dicapai-{{$tp->tp_id}}" wire:model="tp_dicapai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$tp->tp_id}}"
                                                @if(isset($tp_belum_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id]) && $tp_belum_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id])
                                                disabled
                                                @endif
                                                >
                                                <label class="form-check-label" for="tp_dicapai-{{$tp->tp_id}}">
                                                    {{$tp->deskripsi}}
                                                </label>
                                            </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($data_tp as $tp)
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" value="{{$tp->tp_id}}" id="tp_belum_dicapai-{{$tp->tp_id}}" wire:model="tp_belum_dicapai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$tp->tp_id}}"
                                                @if(isset($tp_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id]) && $tp_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp->tp_id])
                                                disabled
                                                @endif
                                                >
                                                <label class="form-check-label" for="tp_belum_dicapai-{{$tp->tp_id}}">
                                                    {{$tp->deskripsi}}
                                                </label>
                                            </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="4">
                                            <div class="alert alert-danger" role="alert">
                                                <div class="alert-body text-center">
                                                    <h2>Tidak ditemukan data Tujuan Pembelajaran</h2>
                                                    <p>Silahkan tambah data Tujuan Pembelajaran terlebih dahulu <a href="{{route('referensi.tujuan-pembelajaran.tambah')}}">disini</a></p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                --}}
                            </table>
                        </div>
                    </div>                        
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success fly-button {{($show && count($data_tp)) ? '' : 'd-none'}}">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('data_rombongan_belajar', event => {
        $('#rombongan_belajar_id').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $('#mata_pelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_pembelajaran', event => {
        $('#mata_pelajaran_id').html('<option value="">== Pilih Mata Pelajaran ==</option>')
        $.each(event.detail.data_pembelajaran, function (i, item) {
            $('#mata_pelajaran_id').append($('<option>', { 
                value: item.mata_pelajaran_id,
                text : item.nama_mata_pelajaran
            }));
        });
    })
    window.addEventListener('data_siswa', event => {
        console.log(event);
        $('[data-bs-toggle="tooltip"]').tooltip()
    })
</script>
@endpush
@push('styles')
<style>
.fly-button {
  bottom: 11%;
  position: fixed;
  right: 79px;
  z-index: 1031;
}
.form-check-input{
    float:none;
    margin: 0;
}
.form-check-inline{
    margin: 0;
}
</style>
@endpush