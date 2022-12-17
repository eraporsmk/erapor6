<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:ignore.self wire:submit.prevent="store">
                <div class="card-body">
                    @include('livewire.formulir-umum')
                    {{--
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
                            <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Rombongan Belajar ==" wire:change="changeRombel">
                                <option value="">== Pilih Rombongan Belajar ==</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2" wire:ignore>
                        <label for="mata_pelajaran_id" class="col-sm-3 col-form-label">Mata Pelajaran</label>
                        <div class="col-sm-9">
                            <select id="mata_pelajaran_id" class="form-select" wire:model="mata_pelajaran_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Mata Pelajaran ==" wire:change="changePembelajaran">
                                <option value="">== Pilih Mata Pelajaran ==</option>
                            </select>
                        </div>
                    </div>
                    --}}
                    <div class="row mb-2{{($show_reset) ? '' : ' d-none'}}">
                        <label class="col-sm-3 col-form-label">Reset Capaian Kompetensi</label>
                        <div class="col-sm-9">
                            <button type="button" class="btn btn-danger" wire:click="resetData">Reset Capaian Kompetensi</button>
                        </div>
                    </div>
                    <div class="table-responsive{{($show) ? '' : ' d-none'}}">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" rowspan="2">Nama Peserta Didik</th>
                                    <th class="text-center" rowspan="2">Nilai Akhir</th>
                                    <th colspan="2" class="text-center">Capaian Kompetensi</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Kompetensi yang sudah dicapai</th>
                                    <th class="text-center">Kompetensi yang perlu ditingkatkan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_siswa as $siswa)
                                    <tr @if($loop->iteration % 2 == 0) class="table-secondary" @endif>
                                        <td class="align-middle">{{$siswa->nama}}</td>
                                        <td class="align-middle text-center">
                                            {{($siswa->anggota_rombel->nilai_akhir_mapel) ? $siswa->anggota_rombel->nilai_akhir_mapel->nilai : 0}}
                                        </td>
                                        <td>
                                            <textarea wire:model.defer="deskripsi_kompeten.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="textarea form-control @error('deskripsi_kompeten.'.$siswa->anggota_rombel->anggota_rombel_id) is-invalid @enderror" rows="5"></textarea>
                                            @error('deskripsi_kompeten.'.$siswa->anggota_rombel->anggota_rombel_id) 
                                            <span class="text-danger fw-bold">{{$message}} </span>
                                            @enderror
                                        </td>
                                        <td>
                                            <textarea wire:model.defer="deskripsi_inkompeten.{{$siswa->anggota_rombel->anggota_rombel_id}}" class="textarea form-control @error('deskripsi_inkompeten.'.$siswa->anggota_rombel->anggota_rombel_id) is-invalid @enderror" rows="5"></textarea>
                                            @error('deskripsi_inkompeten.'.$siswa->anggota_rombel->anggota_rombel_id) 
                                            <span class="text-danger fw-bold">{{$message}} </span>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer{{($show) ? '' : ' d-none'}}">
                    <button type="submit" class="btn btn-success fly-button">SIMPAN</button>
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
</style>
@endpush