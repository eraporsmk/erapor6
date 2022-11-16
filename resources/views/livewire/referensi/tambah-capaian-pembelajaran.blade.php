<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <!--div class="alert alert-info" role="alert">
                    <div class="alert-body">Tambah Data Capaian Pembelajaran hanya untuk Mata Pelajaran Muatan Lokal!</div>
                </div-->
                <div class="row mb-2">
                    <label for="semester_id" class="col-sm-3 col-form-label">Tahun Pelajaran</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" readonly wire:model="semester_id">
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="tingkat" class="col-sm-3 col-form-label">Tingkat Kelas</label>
                    <div class="col-sm-9">
                        <select id="tingkat" class="form-select select2" wire:model="tingkat" wire:change="changeTingkat" aria-describedby="tingkat">
                            <option value="">== Pilih Tingkat Kelas ==</option>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                            <option value="13">Kelas 13</option>
                        </select>
                        @error('tingkat')
                        <span id="tingkat" class="mt-1">
                            <span class="text-danger">{{ $message }}</span>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="rombongan_belajar_id" class="col-sm-3 col-form-label">Rombongan Belajar</label>
                    <div class="col-sm-9">
                        <select id="rombongan_belajar_id" class="form-select select2" wire:model="rombongan_belajar_id" wire:change="changeRombel" aria-describedby="rombongan_belajar_id">
                            <option value="">== Pilih Rombongan Belajar ==</option>
                            @if($data_rombongan_belajar)
                            @foreach ($data_rombongan_belajar as $rombongan_belajar)
                            <option value="{{$rombongan_belajar->rombongan_belajar_id}}">{{$rombongan_belajar->nama}}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('rombongan_belajar_id')
                        <span id="rombongan_belajar_id" class="mt-1">
                            <span class="text-danger">{{ $message }}</span>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="mata_pelajaran_id" class="col-sm-3 col-form-label">Mata Pelajaran</label>
                    <div class="col-sm-9">
                        <select id="mata_pelajaran_id" class="form-select select2" wire:model="mata_pelajaran_id" aria-describedby="mata_pelajaran_id">
                            <option value="">== Pilih Mata Pelajaran ==</option>
                            @if($data_pembelajaran)
                            @foreach ($data_pembelajaran as $pembelajaran)
                            <option value="{{$pembelajaran->mata_pelajaran_id}}">{{$pembelajaran->nama_mata_pelajaran}}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('mata_pelajaran_id')
                        <span id="mata_pelajaran_id" class="mt-1">
                            <span class="text-danger">{{ $message }}</span>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="elemen" class="col-sm-3 col-form-label">Elemen</label>
                    <div class="col-sm-9">
                        <input type="text" wire:model="elemen" id="elemen" class="form-control" aria-describedby="elemen"/>
                        @error('elemen')
                        <span id="elemen" class="mt-1">
                            <span class="text-danger">{{ $message }}</span>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2">
                    <label for="capaian_pembelajaran" class="col-sm-3 col-form-label">Capaian Pembelajaran</label>
                    <div class="col-sm-9">
                        <textarea rows="5" wire:model="capaian_pembelajaran" id="capaian_pembelajaran" class="form-control" aria-describedby="capaian_pembelajaran"></textarea>
                        @error('capaian_pembelajaran')
                        <span id="capaian_pembelajaran" class="mt-1">
                            <span class="text-danger">{{ $message }}</span>
                        </span>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" wire:click.prevent="store()">Simpan</button>
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
@push('script')
<script>
    $(document).ready(function () {
        select2 = $('.select2')
            .not('.select2-hidden-accessible')
            .select2({
                allowClear: true
            });
        select2.on('select2:select', (event) => {
            //model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value);
            $(event.target.id).val(event.target.value).trigger('change');
            if (event.target.hasAttribute('multiple')) { 
                model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value); 
            } else { 
                model = event.params.data.id 
            }
        });
        select2.on('select2:unselect', (event) => {
            //model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value);
            if (event.target.hasAttribute('multiple')) { 
                model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value); 
            } else { 
                model = event.params.data.id 
            }
        });
        
        //$watch('model', (value) => {
            //select2.val(value).trigger('change');
            //Livewire.emit('select2', value)
        //});
    });
  </script>
@endpush