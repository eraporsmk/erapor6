<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Prestasi;
use App\Models\Peserta_didik;

class PrestasiPd extends Component
{
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $judul = 'Tambah Data';
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function loadPerPage(){
        $this->resetPage();
    }
    public $sortby = 'created_at';
    public $sortbydesc = 'DESC';
    public $per_page = 10;

    public $show = FALSE;
    public $anggota_rombel_id;
    public $jenis_prestasi;
    public $keterangan_prestasi;
    public $prestasi_id;
    public $nama_pd;

    protected $listeners = [
        'callModal',
        'hapus_data'
    ];

    protected $rules = [
        'anggota_rombel_id' => 'required',
        'jenis_prestasi' => 'required',
        'keterangan_prestasi' => 'required',
    ];
    protected $messages = [
        'anggota_rombel_id.required' => 'Peserta Didik tidak boleh kosong.',
        'jenis_prestasi.required' => 'Jenis Prestasi tidak boleh kosong.',
        'keterangan_prestasi.required' => 'Keterangan Prestasi tidak boleh kosong.',
    ];

    public function render()
    {
        return view('livewire.laporan.prestasi-pd', [
            'collection' => Prestasi::whereHas('anggota_rombel', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 1);
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                });
            })->with(['peserta_didik' => function($query){
                $query->with(['anggota_rombel' => function($query){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('jenis_rombel', 1);
                        $query->where('semester_id', session('semester_aktif'));
                        $query->where('guru_id', $this->loggedUser()->guru_id);
                    });
                    $query->with(['rombongan_belajar' => function($query){
                        $query->where('jenis_rombel', 1);
                        $query->where('semester_id', session('semester_aktif'));
                        $query->where('guru_id', $this->loggedUser()->guru_id);
                    }]);
                }]);
            }])->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($query) {
                    $query->whereHas('anggota_rombel', function($query){
                        $query->whereHas('rombongan_belajar', function($query){
                            $query->where('jenis_rombel', 1);
                            $query->where('semester_id', session('semester_aktif'));
                            $query->where('guru_id', $this->loggedUser()->guru_id);
                        });
                    });
                    $query->where('jenis_prestasi', 'ILIKE', '%' . $this->search . '%');
                    $query->orWhere('keterangan_prestasi', 'ILIKE', '%' . $this->search . '%');
                    $query->whereHas('anggota_rombel', function($query){
                        $query->whereHas('rombongan_belajar', function($query){
                            $query->where('jenis_rombel', 1);
                            $query->where('semester_id', session('semester_aktif'));
                            $query->where('guru_id', $this->loggedUser()->guru_id);
                        });
                    });
                    $query->orWhereHas('anggota_rombel', function($query){
                        $query->whereHas('rombongan_belajar', function($query){
                            $query->where('jenis_rombel', 1);
                            $query->where('semester_id', session('semester_aktif'));
                            $query->where('guru_id', $this->loggedUser()->guru_id);
                        });
                        $query->whereHas('peserta_didik', function($query){
                            $query->where('nama', 'ILIKE', '%' . $this->search . '%');
                            $query->orWhere('nisn', 'ILIKE', '%' . $this->search . '%');
                        });
                    });
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Prestasi Peserta Didik"]
            ],
            'tombol_add' => [
                'wire' => 'addModal',
                'color' => 'primary',
                'text' => 'Tambah Data',
                'attributes' => [
                    'data-bs-toggle=modal',
                    'data-bs-target=#addModal',
                ]
            ],
            'data_siswa' => Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 1);
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                    $query->where('semester_id', session('semester_aktif'));
                });
            })->with(['anggota_rombel' => function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 1);
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                });
            }])->orderBy('nama')->get(),
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function store(){
        $this->validate();
        Prestasi::create([
            'sekolah_id' => session('sekolah_id'),
            'anggota_rombel_id' => $this->anggota_rombel_id,
            'jenis_prestasi' => $this->jenis_prestasi,
            'keterangan_prestasi' => $this->keterangan_prestasi,
            'last_sync' => now(),
        ]);
        $this->emit('close-modal');
        $this->reset(['show', 'anggota_rombel_id', 'jenis_prestasi', 'keterangan_prestasi']);
        $this->emit('confirmed');
        $this->alert('success', 'Berhasil', [
            'text' => 'Data Prestasi Peserta Didik berhasil disimpan'
        ]);
    }
    public function addModal(){
        
    }
    public function updatedAnggotaRombelId($value){
        if($value){
            $this->show = TRUE;
        }
    }
    public function callModal(){
        //$this->emit('showModal');        
    }
    public function getId($id, $aksi){
        $this->prestasi_id = $id;
        if($aksi == 'edit'){
            $this->judul = 'Ubah Data';
            $data = Prestasi::with(['peserta_didik' => function($query){
                $query->select('peserta_didik.peserta_didik_id', 'nama');
            }])->find($this->prestasi_id);
            $this->show = TRUE;
            $this->emit('showModal');
            $this->anggota_rombel_id = $data->anggota_rombel_id;
            $this->jenis_prestasi = $data->jenis_prestasi;
            $this->keterangan_prestasi = $data->keterangan_prestasi;
            $this->nama_pd = $data->peserta_didik->nama;
            $this->dispatchBrowserEvent('anggota_rombel_id', ['anggota_rombel_id' => $this->anggota_rombel_id]);
            $this->dispatchBrowserEvent('jenis_prestasi', ['jenis_prestasi' => $this->jenis_prestasi]);
            $this->dispatchBrowserEvent('pharaonic.select2.init');
        } else {
            $this->alert('question', 'Apakah Anda yakin?', [
                'text' => 'Tindakan ini tidak dapat dikembalikan!',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'hapus_data',
                'showCancelButton' => true,
                'cancelButtonText' => 'Batal',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        }
    }
    public function hapus_data(){
        $data = Prestasi::find($this->prestasi_id);
        if($data && $data->delete()){
            $this->alert('success', 'Berhasil', [
                'text' => 'Data Prestasi Peserta Didik berhasil dihapus!'
            ]);
        } else {
            $this->alert('error', 'Berhasil', [
                'text' => 'Data Prestasi Peserta Didik gagal dihapus. Silahkan coba beberapa saat lagi!'
            ]);
        }
    }
    public function perbaharui(){
        $this->validate();
        $data = Prestasi::find($this->prestasi_id);
        $data->jenis_prestasi = $this->jenis_prestasi;
        $data->keterangan_prestasi = $this->keterangan_prestasi;
        $data->last_sync = now();
        $data->save();
        $this->emit('close-modal');
        $this->reset(['show', 'anggota_rombel_id', 'jenis_prestasi', 'keterangan_prestasi']);
        $this->emit('confirmed');
        $this->alert('success', 'Berhasil', [
            'text' => 'Data Prestasi Peserta Didik berhasil diperbaharui'
        ]);
    }
}
