<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Catatan_ppk;
use App\Models\Peserta_didik;
use App\Models\Sikap;
use App\Models\Nilai_sikap;
use App\Models\Nilai_karakter;

class NilaiKarakter extends Component
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
    public $nama_siswa;
    public $data_siswa = [];
    public $all_sikap = [];
    public $nilai_sikap = [];
    public $deskripsi = [];
    public $capaian;
    public $catatan_ppk;
    public $catatan_ppk_id;

    public $show = FALSE;
    public $anggota_rombel_id = '';
    public function getListeners()
    {
        return [
            'showModal',
            'showAlert',
            'anggota_rombel_id',
            'delete'
        ];
    }

    public function render()
    {
        return view('livewire.laporan.nilai-karakter', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Nilai Karakter"]
            ],
            'tombol_add' => [
                'wire' => 'addModal',
                'color' => 'primary',
                'text' => 'Tambah Data',
            ],
            'collection' => Catatan_ppk::whereHas('anggota_rombel', function($query){
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
        //$this->reset(['show', 'pola', 'anggota_wirausaha', 'anggota_wirausaha_id', 'jenis_usaha', 'nama_produk', 'kelompok', 'anggota_rombel_id']);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function updatedAnggotaRombelId($value)
    {
        //$this->reset(['show', 'pola', 'anggota_wirausaha', 'anggota_wirausaha_id', 'jenis_usaha', 'nama_produk', 'kelompok']);
        if($this->anggota_rombel_id){
            $pd = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->where('anggota_rombel_id', $this->anggota_rombel_id);
            })->first();
            $this->show = TRUE;
            $this->nama_siswa = ($pd) ? $pd->nama : '-';
            $this->nilai_sikap = Nilai_sikap::with(['guru' => function($query){
                $query->select('guru_id', 'nama');
            }])->where('anggota_rombel_id', $this->anggota_rombel_id)->get();
            $catatan_ppk = Catatan_ppk::with(['nilai_karakter'])->where('anggota_rombel_id', $this->anggota_rombel_id)->first();
            if($catatan_ppk){
                foreach($catatan_ppk->nilai_karakter as $nilai_karakter){
                    $this->deskripsi[$nilai_karakter->sikap_id] = $nilai_karakter->deskripsi;
                }
                $this->capaian = $catatan_ppk->capaian;
            }
        }
    }
    public function store(){
        $catatan_ppk = Catatan_ppk::updateOrCreate(
			[
                'anggota_rombel_id' => $this->anggota_rombel_id
            ],
			[
                'sekolah_id' => session('sekolah_id'),
                'capaian' => $this->capaian,
                'last_sync'	=> now(),
            ]
		);
		foreach ($this->deskripsi as $sikap_id => $deskripsi) {
            if($deskripsi){
                Nilai_karakter::updateOrCreate(
                    [
                        'catatan_ppk_id' => $catatan_ppk->catatan_ppk_id, 
                        'sikap_id' => $sikap_id
                    ],
                    [
                        'sekolah_id' => session('sekolah_id'),
                        'deskripsi' => $deskripsi,
                        'last_sync'	=> now()
                    ]
                );
            } else {
                Nilai_karakter::where('catatan_ppk_id', $catatan_ppk->catatan_ppk_id)->where('sikap_id', $sikap_id)->delete();
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
        $this->all_sikap = Sikap::whereNull('sikap_induk')->with('sikap')->get();
    }
    public function showAlert(){
        $this->alert('success', 'Nilai Karakter berhasil disimpan');
    }
    public function getID($catatan_ppk_id, $aksi){
        $this->catatan_ppk_id = $catatan_ppk_id;
        $this->catatan_ppk = Catatan_ppk::find($this->catatan_ppk_id);
        if($aksi == 'view'){
            $this->emit('show-modal');
        } else {
            $this->alert('question', 'Apakah Anda yakin?', [
                'text' => 'Tindakan ini tidak dapat dikembalikan',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'delete',
                'showCancelButton' => true,
                'cancelButtonText' => 'Batal',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        }
    }
    public function delete(){
        $data = $this->catatan_ppk;
        if($data->delete()){
            $this->alert('success', 'Nilai karakter berhasil dihapus!', [
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'confirmed',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        } else {
            $this->alert('error', 'Nilai karakter gagal dihapus!. Coba beberapa saat lagi!', [
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'confirmed',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        }
    }
}
