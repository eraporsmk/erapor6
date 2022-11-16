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
                        <select id="mata_pelajaran_id" class="form-select select2" wire:model="mata_pelajaran_id" aria-describedby="mata_pelajaran_id" wire:change="changeMapel">
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
                    <label for="cp_id" class="col-sm-3 col-form-label">Capaian Pembelajaran (CP)</label>
                    <div class="col-sm-9">
                        <select id="cp_id" class="form-select select2" wire:model="cp_id" aria-describedby="cp_id" wire:change="changeCp">
                            <option value="">== Pilih Capaian Pembelajaran (CP) ==</option>
                            @if($data_cp)
                            @foreach ($data_cp as $cp)
                            <option value="{{$cp->cp_id}}">(Fase {{$cp->fase}}) {{Illuminate\Support\Str::limit($cp->deskripsi, 100)}}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('cp_id')
                        <span id="cp_id" class="mt-1">
                            <span class="text-danger">{{ $message }}</span>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-2 {{($show) ? '' : 'd-none'}}">
                    <div class="col-6">
                        <input class="form-control" type="file" wire:model="template_excel">
                        @error('template_excel') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-6 d-grid">
                        <a class="btn btn-primary" href="{{route('unduhan.template-tp', ['cp_id' => $cp_id])}}">Unduh Template TP</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
@push('script')
@endpush