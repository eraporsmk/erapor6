<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Mata Pelajaran yang diampu di Tahun Pelajaran {{ session('semester_id') }}</h4>
                <table class="table table-bordered {{ table_striped() }}">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">No</th>
                            <th class="text-center align-middle">Mata Pelajaran</th>
                            <th class="text-center align-middle">Rombel</th>
                            <th class="text-center align-middle">Wali Kelas</th>
                            <th class="text-center align-middle">Jumlah Peserta Didik</th>
                            <th class="text-center">Jml Peserta Didik Dinilai</th>
                            <th class="text-center">Detil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        ?>
                        @forelse ($rombel_diampu as $key => $mapel_diampu)
                            @foreach ($mapel_diampu->pembelajaran as $item)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $item->nama_mata_pelajaran }}</td>
                                    <td>{{ $mapel_diampu->nama }}</td>
                                    <td>{{ $mapel_diampu->wali_kelas ? $mapel_diampu->wali_kelas->nama_lengkap : '-' }}
                                    </td>
                                    <td class="text-center">{{ $item->anggota_rombel_count }}</td>
                                    <td class="text-center">
                                        {{ $item->anggota_rombel()->whereHas('nilai_akhir_mapel', function ($query) use ($item) {
                                                $query->where('pembelajaran_id', $item->pembelajaran_id);
                                            })->count() }}
                                    </td>
                                    <td class="text-center"><button class="btn btn-sm btn-info" wire:click="detil('{{$item->pembelajaran_id}}')">Detil</button></td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data untuk ditampilkan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @role('wali', session('semester_id'))
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Anda adalah Wali Kelas Rombongan Belajar
                        {{ $rombongan_belajar ? $rombongan_belajar->nama : '-' }}</h4>
                    <h5 class="card-title">Daftar Mata Pelajaran di Rombongan Belajar
                        {{ $rombongan_belajar ? $rombongan_belajar->nama : '-' }}</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">#</th>
                                <th class="text-center align-middle">Mata Pelajaran</th>
                                <th class="text-center align-middle">Guru Mata Pelajaran</th>
                                <th class="text-center align-middle">Jml Peserta Didik</th>
                                <th class="text-center align-middle">Jml Peserta Didik Dinilai</th>
                                <th class="text-center">Detil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rombongan_belajar)
                                @forelse ($rombongan_belajar->pembelajaran as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_mata_pelajaran }}</td>
                                        <td>{{ $item->pengajar ? $item->pengajar->nama_lengkap : $item->guru->nama_lengkap }}
                                        </td>
                                        <td class="text-center">{{ $item->anggota_rombel_count }}</td>
                                        <td class="text-center">
                                            {{ $item->anggota_rombel()->whereHas('nilai_akhir_mapel', function ($query) use ($item) {
                                                    $query->where('pembelajaran_id', $item->pembelajaran_id);
                                                })->count() }}
                                        </td>
                                        <td class="text-center"><button class="btn btn-sm btn-info" wire:click="detil('{{$item->pembelajaran_id}}')">Detil</button></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data untuk ditampilkan</td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($rombel_pilihan)
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Anda adalah Wali Kelas Rombel Matpel Pilihan
                            {{ $rombel_pilihan ? $rombel_pilihan->nama : '-' }}</h4>
                        <h5 class="card-title">Daftar Mata Pelajaran di Rombel Matpel Pilihan
                            {{ $rombel_pilihan ? $rombel_pilihan->nama : '-' }}</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">#</th>
                                    <th class="text-center align-middle">Mata Pelajaran</th>
                                    <th class="text-center align-middle">Guru Mata Pelajaran</th>
                                    <th class="text-center align-middle">Jml Peserta Didik</th>
                                    <th class="text-center align-middle">Jml Peserta Didik Dinilai</th>
                                    <th class="text-center">Detil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($rombel_pilihan)
                                    @forelse ($rombel_pilihan->pembelajaran as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_mata_pelajaran }}</td>
                                            <td>{{ $item->pengajar ? $item->pengajar->nama_lengkap : $item->guru->nama_lengkap }}
                                            </td>
                                            <td class="text-center">{{ $item->anggota_rombel_count }}</td>
                                            <td class="text-center">
                                                {{ $item->anggota_rombel()->whereHas('nilai_akhir_mapel', function ($query) use ($item) {
                                                        $query->where('pembelajaran_id', $item->pembelajaran_id);
                                                    })->count() }}
                                            </td>
                                            <td class="text-center"><button class="btn btn-sm btn-info" wire:click="detil('{{$item->pembelajaran_id}}')">Detil</button></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data untuk ditampilkan</td>
                                        </tr>
                                    @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endrole
        @role('waka', session('semester_id'))
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Progres Penilaian Tahun Pelajaran {{session('semester_id')}}</h4>
                    @include('components.navigasi-table')
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Rombel</th>
                                    <th class="text-center align-middle">Mata Pelajaran</th>
                                    <th class="text-center align-middle">Guru Mata Pelajaran</th>
                                    <th class="text-center align-middle">Jumlah Peserta Didik</th>
                                    <th class="text-center align-middle">Jml Peserta Didik Dinilai</th>
                                    <th class="text-center align-middle">Detil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($collection->sortBy('rombongan_belajar.tingkat')->sortBy('rombongan_belajar.nama') as $item)
                                    <tr>
                                        <td>{{ $item->rombongan_belajar->nama }}</td>
                                        <td>{{ $item->nama_mata_pelajaran }}</td>
                                        <td>{{ $item->guru_pengajar_id ? $item->pengajar->nama_lengkap : $item->guru->nama_lengkap }}</td>
                                        <td class="text-center">{{ $item->anggota_rombel_count }}</td>
                                        <td class="text-center">
                                            {{ $item->anggota_rombel()->whereHas('nilai_akhir_mapel', function ($query) use ($item) {
                                                    $query->where('pembelajaran_id', $item->pembelajaran_id);
                                                })->count() }}
                                        </td>
                                        <td class="text-center"><button class="btn btn-sm btn-info" wire:click="detil('{{$item->pembelajaran_id}}')">Detil</button></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">Tidak ada data untuk ditampilkan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-between mt-2">
                        <div class="col-6">
                            @if ($collection->count())
                                <p>Menampilkan {{ $collection->firstItem() }} sampai
                                    {{ $collection->firstItem() + $collection->count() - 1 }} dari
                                    {{ $collection->total() }} data</p>
                            @endif
                        </div>
                        <div class="col-6">
                            {{ $collection->onEachSide(1)->links('components.custom-pagination-links-view') }}
                        </div>
                    </div>
                </div>
            </div>
        @endrole
    </div>
    @include('livewire.dashboard.detil-penilaian')
    @include('components.loader')
</div>
@push('scripts')
<script>
    Livewire.on('show-detil', event => {
        $('#detilModal').modal('show');
    })
</script>
@endpush