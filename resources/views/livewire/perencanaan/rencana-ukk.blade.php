<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <label for="semester_id" class="col-sm-3 col-form-label">Tahun Ajaran</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" readonly wire:model="semester_id">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="tingkat" class="col-sm-3 col-form-label">Tingkat Kelas</label>
                    <div class="col-sm-9" wire:ignore>
                        <select id="tingkat" class="form-select" wire:model="tingkat" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Tingkat Kelas ==">
                            <option value="">== Pilih Tingkat Kelas ==</option>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                            <option value="13">Kelas 13</option>
                        </select>
                        @error('tingkat')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Rombongan Belajar</label>
                    <div class="col-sm-9" wire:ignore>
                        <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Rombongan Belajar ==">
                            <option value="">== Pilih Rombongan Belajar ==</option>
                        </select>
                        @error('rombongan_belajar_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="penguji_internal" class="col-sm-3 col-form-label">Penguji Internal</label>
                    <div class="col-sm-9" wire:ignore>
                        <select id="penguji_internal" class="form-select" wire:model="penguji_internal" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Penguji Internal ==">
                            <option value="">== Pilih Penguji Internal ==</option>
                        </select>
                        @error('penguji_internal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="penguji_eksternal" class="col-sm-3 col-form-label">Penguji Eksternal</label>
                    <div class="col-sm-9" wire:ignore>
                        <select id="penguji_eksternal" class="form-select" wire:model="penguji_eksternal" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Penguji Eksternal ==">
                            <option value="">== Pilih Penguji Eksternal ==</option>
                        </select>
                        @error('penguji_eksternal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="paket_kompetensi" class="col-sm-3 col-form-label">Paket Kompetensi</label>
                    <div class="col-sm-9" wire:ignore>
                        <select id="paket_kompetensi" class="form-select" wire:model="paket_kompetensi" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Paket Kompetensi ==">
                            <option value="">== Pilih Paket Kompetensi ==</option>
                        </select>
                        @error('paket_kompetensi')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="tanggal" class="col-sm-3 col-form-label">Tanggal Sertifikat</label>
                    <div class="col-sm-9">
                        <x-date-picker wire:model.lazy="tanggal" class="form-control"/>
                        @error('tanggal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2 {{($show) ? '' : 'd-none'}}">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%"></th>
                                    <th class="text-center" width="55%"></th>
                                    <th class="text-center" width="45%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collection as $siswa)
                                <tr>
                                    <td class="text-center">
                                        <input wire:model="siswa_dipilih.{{$siswa->anggota_rombel->anggota_rombel_id}}" value="{{$siswa->peserta_didik_id}}" type="checkbox">
                                        {{--
                                        @if($siswa->nilai_ukk && $rencana_ukk)
                                        <input type="checkbox" checked="checked" disabled="disabled" />
                                        @else
                                        <input wire:model="siswa_dipilih.{{$siswa->anggota_rombel->anggota_rombel_id}}" value="{{$siswa->peserta_didik_id}}" type="checkbox">
                                        @endif
                                        --}}
                                    </td>
                                    <td>{{$siswa->nama}}</td>
                                    <td>{{($rencana_ukk) ? $rencana_ukk->paket_ukk->nama_paket_id : '-'}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary {{($show) ? '' : 'd-none'}}" wire:click.prevent="store()">Simpan</button>
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
@push('scripts')
<script>
    window.addEventListener('data_tingkat', event => {
        $('#rombongan_belajar_id').html('<option value="">== Pilih Rombongan Belajar ==</option>')
        $('#penguji_internal').html('<option value="">== Pilih Penguji Internal ==</option>')
        $('#penguji_eksternal').html('<option value="">== Pilih Penguji Eksternal ==</option>')
        $('#paket_kompetensi').html('<option value="">== Pilih Paket Kompetensi ==</option>')
    });
    window.addEventListener('data_rombongan_belajar', event => {
        $('#penguji_internal').html('<option value="">== Pilih Penguji Internal ==</option>')
        $('#penguji_eksternal').html('<option value="">== Pilih Penguji Eksternal ==</option>')
        $('#paket_kompetensi').html('<option value="">== Pilih Paket Kompetensi ==</option>')
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_internal', event => {
        $.each(event.detail.data_internal, function (i, item) {
            $('#penguji_internal').append($('<option>', { 
                value: item.guru_id,
                text : item.nama_lengkap
            }));
        });
    })
    window.addEventListener('data_eksternal', event => {
        $.each(event.detail.data_eksternal, function (i, item) {
            $('#penguji_eksternal').append($('<option>', { 
                value: item.guru_id,
                text : item.nama_lengkap
            }));
        });
    })
    window.addEventListener('paket_ukk', event => {
        console.log(event.detail.paket_ukk);
        $.each(event.detail.paket_ukk, function (i, item) {
            $('#paket_kompetensi').append($('<option>', { 
                value: item.paket_ukk_id,
                text : item.nama_paket_id
            }));
        });
    })
</script>
@endpush
