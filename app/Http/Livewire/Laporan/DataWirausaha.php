<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Kewirausahaan;
use App\Models\Anggota_kewirausahaan;
use App\Models\Peserta_didik;

class DataWirausaha extends Component
{
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
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
    public $pola;
    public $anggota_wirausaha = [];
    public $jenis_usaha;
    public $nama_produk;
    public $kelompok = FALSE;
    public $data_siswa = [];
    public $anggota_rombel_id = '';
    public $anggota_wirausaha_id = [];
    public function getListeners()
    {
        return [
            'showModal',
            'showAlert',
            'anggota_rombel_id',
            'pola',
        ];
    }

    public function render()
    {
        return view('livewire.laporan.data-wirausaha', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Data Kewirausahaan"]
            ],
            'tombol_add' => [
                'wire' => 'addModal',
                'color' => 'primary',
                'text' => 'Tambah Data',
            ],
            'collection' => Kewirausahaan::whereHas('anggota_rombel', function($query){
                if($this->loggedUser()->hasRole('waka')){
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('jenis_rombel', 1);
                    });
                } else {
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('guru_id', $this->loggedUser()->guru_id);
                        $query->where('jenis_rombel', 1);
                    });
                }
            })->with([
                'peserta_didik' => function($query){
                    $query->whereHas('anggota_rombel', function($query){
                        if($this->loggedUser()->hasRole('waka')){
                            $query->where('sekolah_id', session('sekolah_id'));
                            $query->where('semester_id', session('semester_aktif'));
                            $query->whereHas('rombongan_belajar', function($query){
                                $query->where('jenis_rombel', 1);
                            });
                        } else {
                            $query->where('sekolah_id', session('sekolah_id'));
                            $query->where('semester_id', session('semester_aktif'));
                            $query->whereHas('rombongan_belajar', function($query){
                                $query->where('guru_id', $this->loggedUser()->guru_id);
                                $query->where('jenis_rombel', 1);
                            });
                        }
                    });
                },
                'anggota_kewirausahaan'
            ])->orderBy($this->sortby, $this->sortbydesc)
            ->when($this->search, function($ptk) {
                $ptk->where('nama', 'ILIKE', '%' . $this->search . '%')
                ->orWhere('nuptk', 'ILIKE', '%' . $this->search . '%');
            })->paginate($this->per_page),
        ]);
    }
    public function addModal(){
        $this->emit('showModal');
    }
    public function showModal(){
        $this->reset(['show', 'pola', 'anggota_wirausaha', 'anggota_wirausaha_id', 'jenis_usaha', 'nama_produk', 'kelompok', 'anggota_rombel_id']);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function anggota_rombel_id(){
        $this->reset(['show', 'pola', 'anggota_wirausaha', 'anggota_wirausaha_id', 'jenis_usaha', 'nama_produk', 'kelompok']);
        if($this->anggota_rombel_id){
            $this->show = TRUE;
        }
    }
    public function updatedAnggotaRombelId($value)
    {
        $this->reset(['show', 'pola', 'anggota_wirausaha', 'anggota_wirausaha_id', 'jenis_usaha', 'nama_produk', 'kelompok']);
        if($this->anggota_rombel_id){
            $this->show = TRUE;
        }
    }
    public function updatedPola($value){
        $this->reset(['anggota_wirausaha', 'anggota_wirausaha_id', 'jenis_usaha', 'nama_produk', 'kelompok']);
        if($this->pola){
            if($this->pola == 'Kelompok'){
                $this->kelompok = TRUE;
                $this->anggota_wirausaha = Peserta_didik::whereHas('anggota_rombel', function($query){
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('guru_id', $this->loggedUser()->guru_id);
                        $query->where('jenis_rombel', 1);
                    });
                })->with(['anggota_rombel' => function($query){
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('guru_id', $this->loggedUser()->guru_id);
                        $query->where('jenis_rombel', 1);
                    });
                }])->orderBy('nama')->get();
            }
            $this->dispatchBrowserEvent('anggota_wirausaha', ['anggota_wirausaha' => $this->anggota_wirausaha]);
        }
    }
    public function pola(){
        $this->reset(['anggota_wirausaha', 'anggota_wirausaha_id', 'jenis_usaha', 'nama_produk', 'kelompok']);
        if($this->pola){
            if($this->pola == 'Kelompok'){
                $this->kelompok = TRUE;
                $this->anggota_wirausaha = Peserta_didik::whereHas('anggota_rombel', function($query){
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('guru_id', $this->loggedUser()->guru_id);
                        $query->where('jenis_rombel', 1);
                    });
                })->with(['anggota_rombel' => function($query){
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('guru_id', $this->loggedUser()->guru_id);
                        $query->where('jenis_rombel', 1);
                    });
                }])->orderBy('nama')->get();
            }
        }
    }
    public function store(){
        $kewirausahaan = Kewirausahaan::create(
            [
                'anggota_rombel_id' => $this->anggota_rombel_id,
                'sekolah_id' => session('sekolah_id'),
                'pola' => $this->pola,
                'jenis' => $this->jenis_usaha,
                'nama_produk' => $this->nama_produk,
                'last_sync' => now(),
            ]
        );
        if($this->anggota_wirausaha_id){
            foreach($this->anggota_wirausaha_id as $anggota_rombel_id){
                Anggota_kewirausahaan::create(
                    [
                        'kewirausahaan_id' => $kewirausahaan->kewirausahaan_id,
                        'anggota_rombel_id' => $anggota_rombel_id
                    ]
                );       
            }
        }
        $this->emit('showAlert');
        $this->emit('close-modal');
        $this->resetPage();
    }
    public function mount(){
        $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('semester_id', session('semester_aktif'));
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('guru_id', $this->loggedUser()->guru_id);
                $query->where('jenis_rombel', 1);
            });
        })->with(['anggota_rombel' => function($query){
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('semester_id', session('semester_aktif'));
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('guru_id', $this->loggedUser()->guru_id);
                $query->where('jenis_rombel', 1);
            });
        }])->orderBy('nama')->get();
    }
    public function showAlert(){
        $this->alert('success', 'Data Kewirausahaan berhasil disimpan');
    }
}
