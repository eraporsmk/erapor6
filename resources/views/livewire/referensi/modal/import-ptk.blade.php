<div>
    <div wire:ignore.self class="modal fade" id="ptkModal" tabindex="-1" aria-labelledby="ptkModalLabel"
        aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ptkModalLabel">Tambah Data {{$data}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-between mb-2">
                        <div class="col-8">
                            <input class="form-control" type="file" wire:model="file_excel">
                            @error('file_excel')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-4">
                            <div class="d-grid">
                                <a href="/excel/format_excel_{{strtolower($data)}}.xlsx" class="btn btn-warning">UNDUH TEMPLATE</a>
                            </div>
                        </div>
                    </div>
                    @if($imported_data)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NUPTK</th>
                                    <th>NIP</th>
                                    <th>NIK</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Agama</th>
                                    <th>Alamat Jalan</th>
                                    <th>RT</th>
                                    <th>RW</th>
                                    <th>Desa/Kelurahan</th>
                                    <th>Kecamatan</th>
                                    <th>Kodepos</th>
                                    <th>Telp/HP</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($imported_data as $urut => $data)
                                @if(
                                    $errors->has('nama.'.$urut) ||
                                    $errors->has('nik.'.$urut) ||
                                    $errors->has('email.'.$urut)
                                )
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td class="text-danger">{{ $errors->first('nama.'.$urut) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-danger">{{ $errors->first('nik.'.$urut) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-danger">{{ $errors->first('email.'.$urut) }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="nama.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="nuptk.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="nip.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="nik.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="jenis_kelamin.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="tempat_lahir.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="tanggal_lahir.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="agama.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="alamat_jalan.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="rt.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="rw.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="desa_kelurahan.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="kecamatan.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="kodepos.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="telp_hp.{{$urut}}"></td>
                                    <td><input type="text" class="form-control form-control-sm" wire:model="email.{{$urut}}"></td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    @if ($imported_data)
                    <button type="submit" class="btn btn-primary" wire:click.prevent="store()">Simpan</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>