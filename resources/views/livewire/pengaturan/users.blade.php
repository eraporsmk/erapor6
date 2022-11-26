<div>
    @include('panels.breadcrumb')
    <div class="content-body">    
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="row justify-content-between mb-2">
                        <div class="col-4">
                            <div class="d-inline" wire:ignore>
                                <select class="form-select" wire:model="per_page" wire:change="loadPerPage" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-search-off="true">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-inline" wire:ignore>
                                <select class="form-select" wire:model="role_id" wire:change="filterAkses" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-search-off="true" data-placeholder="== Filter Hak Akses ==">
                                    <option value="">== Filter Hak Akses ==</option>
                                    <option value="all">Semua Hak Akses</option>
                                    @foreach($hak_akses as $akses)
                                    <option value="{{$akses->name}}">{{$akses->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" placeholder="Cari data..." wire:model="search">
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Jenis Pengguna</th>
                                <th class="text-center">Terakhir Login</th>
                                <th class="text-center">Password</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($data_user->total())
                            @foreach($data_user as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    {{$user->allPermissions('display_name', session('semester_id'))->unique()->implode('display_name', ', ')}}
                                </td>
                                <td>{{$user->last_login_at}}</td>
                                <td>{!! (\Illuminate\Support\Facades\Hash::check($user->default_password, $user->password)) ? $user->default_password : '<span class="btn btn-sm btn-success"> Custom </span>' !!}</td>
                                <td class="text-center">
                                    <button class="btn btn-success btn-sm" wire:click="view('{{$user->user_id}}')"><i class="fas fa-eye"></i> Detil</button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="6">Tidak ada data untuk ditampilkan</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="row justify-content-between mt-2">
                        <div class="col-4">
                            @if($data_user->count())
                            <p>Menampilkan {{ $data_user->firstItem() }} sampai {{ $data_user->firstItem() + $data_user->count() - 1 }} dari {{ $data_user->total() }} data</p>
                            @endif
                        </div>
                        <div class="col-4">
                            {{ $data_user->onEachSide(1)->links('components.custom-pagination-links-view') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.loader')
    @include('livewire.pengaturan.view')
</div>
@push('scripts')
<script>
    Livewire.on('openView', event => {
        $('#viewModal').modal('show');
    })
    Livewire.on('close-modal', event => {
        $('#viewModal').modal('hide');
    })
</script>
@endpush