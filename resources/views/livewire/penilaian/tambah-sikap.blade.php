<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:ignore.self wire:submit.prevent="store">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            @foreach($all_sikap as $sikap)
                                <th width="20%" class="text-center">{{$sikap->butir_sikap}}</th>
                            @endforeach
                            </thead>
                            <tbody>
                                <tr>
                                @foreach($all_sikap as $sikap)
                                    <td>
                                    <ul style="padding-left:10px;">
                                    @foreach($sikap->sikap as $subsikap)
                                    <li>{{$subsikap->butir_sikap}}</li>
                                    @endforeach
                                    </ul>
                                    </td>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="semester_id" class="col-sm-3 col-form-label">Tahun Ajaran</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" readonly wire:model="semester_id">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="tingkat" class="col-sm-3 col-form-label">Tingkat Kelas</label>
                    <div class="col-sm-9">
                        <div wire:ignore>
                            <select id="tingkat" class="form-select" wire:model="tingkat" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Tingkat Kelas ==" data-search-off="true">
                                <option value="">== Pilih Tingkat Kelas ==</option>
                                <option value="10">Kelas 10</option>
                                <option value="11">Kelas 11</option>
                                <option value="12">Kelas 12</option>
                                <option value="13">Kelas 13</option>
                            </select>
                        </div>
                        @error('tingkat') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Rombongan Belajar</label>
                    <div class="col-sm-9">
                        <div wire:ignore>
                            <select id="rombongan_belajar_id" class="form-select" wire:model="rombongan_belajar_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Rombongan Belajar ==" wire:change="changeRombel">
                                <option value="">== Pilih Rombongan Belajar ==</option>
                            </select>
                        </div>
                        @error('rombongan_belajar_id') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="anggota_rombel_id" class="col-sm-3 col-form-label">Nama Peserta Didik</label>
                    <div class="col-sm-9">
                        <div wire:ignore>
                            <select id="anggota_rombel_id" class="form-select" wire:model="anggota_rombel_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Nama Peserta Didik ==">
                                <option value="">== Pilih Nama Peserta Didik ==</option>
                            </select>
                        </div>
                        @error('anggota_rombel_id') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                    <div class="col-sm-9">
                        <div class="input-group" wire:ignore>
                            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                            <input type="text" class="form-control form-date" wire:model="tanggal" placeholder="{{$placeholder}}" />
                        </div>
                        @error('tanggal') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="butir_sikap" class="col-sm-3 col-form-label">Butir Sikap</label>
                    <div class="col-sm-3">
                        <div wire:ignore>
                            <select wire:model="sikap_id" class="form-control" id="sikap_id" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Butir Sikap ==">
                                <option value="">== Pilih Butir Sikap ==</option>
                                @foreach($all_sikap as $ref_sikap)
                                <option value="{{$ref_sikap->sikap_id}}">{{$ref_sikap->butir_sikap}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('sikap_id') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                    <div class="col-sm-3">
                        <div wire:ignore>
                            <select wire:model="opsi_sikap" class="form-control" id="opsi_sikap" wire:change="showButton" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-placeholder="== Pilih Opsi Sikap ==">
                                <option value="">== Pilih Opsi Sikap ==</option>
                                <option value="1">Positif</option>
                                <option value="2">Negatif</option>
                            </select>
                        </div>
                        @error('opsi_sikap') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="uraian_sikap" class="col-sm-3 col-form-label">Uraian Sikap</label>
                    <div class="col-sm-9">
                        <textarea wire:model="uraian_sikap" id="uraian_sikap" class="form-control"></textarea>
                        @error('uraian_sikap') <span class="text-danger">{{$message}}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary {{($show) ? '' : 'd-none'}}">Simpan</button>
            </div>
            </form>
        </div>
    </div>
    @include('components.loader')
</div>
@push('styles')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endpush
@push('scripts')
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr-id.min.js')) }}"></script>
<script>
    $('.form-date').flatpickr({
        locale: "id",
        altInput: true,
        altFormat: 'd F Y',
        dateFormat: 'Y-m-d'
    });
    window.addEventListener('data_rombongan_belajar', event => {
        $("#rombongan_belajar_id").html('<option value="">== Pilih Rombongan Belajar ==</option>');
        $.each(event.detail.data_rombongan_belajar, function (i, item) {
            $('#rombongan_belajar_id').append($('<option>', { 
                value: item.rombongan_belajar_id,
                text : item.nama
            }));
        });
    })
    window.addEventListener('data_pd', event => {
        $("#anggota_rombel_id").html('<option value="">== Pilih Nama Peserta Didik ==</option>');
        $.each(event.detail.data_pd, function (i, item) {
            $('#anggota_rombel_id').append($('<option>', { 
                value: item.anggota_rombel.anggota_rombel_id,
                text : item.nama
            }));
        });
    })
</script>
@endpush
