<?php

namespace App\Http\Livewire\Perencanaan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Rencana_penilaian;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Kompetensi_dasar;
use App\Models\Teknik_penilaian;
use App\Models\Kd_nilai;
use App\Models\Bobot_keterampilan;

class Keterampilan extends Component
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
    public $nama = 'Keterampilan';
    public $data_rombongan_belajar;
    public $data_pembelajaran;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $pembelajaran_id;
    public $kompetensi_id = 2;
    public $data_kd = [];
    public $placeholder = 'UH/UTS/UAS/Kinerja/Proyek/Portofolio';
    public $data_bentuk_penilaian;
    public $nama_penilaian = [];
    public $bentuk_penilaian = [];
    public $bobot_penilaian = [];
    public $bobot;
    public $kd_select = [];
    public $keterangan_penilaian = [];
    public $rencana;
    public $show = FALSE;
    public $readonly;
    public $metode_id;
    /*protected $rules = [
        'bentuk_penilaian.*' => 'required',
    ];
    protected $messages = [
        'bentuk_penilaian.*.required' => 'Teknik Penilaian tidak boleh kosong!!',
    ];*/
    protected $listeners = ['cancel', 'addModal'];
    public function addModal(){
        $this->reset(['tingkat', 'rombongan_belajar_id', 'pembelajaran_id', 'bobot']);
        $this->dispatchBrowserEvent('resetForm');
    }
    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Perencanaan'], ['name' => "Penilaian Keterampilan"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $this->semester_id = session('semester_id');
        $callback = function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            });
			$query->with(['rombongan_belajar' => function($query){
                $query->select('rombongan_belajar_id', 'nama');
            }]);
			$query->where('sekolah_id', session('sekolah_id'));
			$query->where('guru_id', $this->loggedUser()->guru_id);
			$query->where('semester_id', session('semester_aktif'));
			$query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
			$query->where('sekolah_id', session('sekolah_id'));
			$query->where('semester_id', session('semester_aktif'));
		};
        return view('livewire.perencanaan.keterampilan', [
            'collection' => Rencana_penilaian::with(['pembelajaran' => $callback, 'teknik_penilaian' => function($query){
                $query->select('teknik_penilaian_id', 'nama');
            }])->where(function($query) use ($callback){
                $query->where('kompetensi_id', $this->kompetensi_id);
                $query->whereHas('pembelajaran', $callback);
            })
            ->withCount('kd_nilai')
            ->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($query) {
                    $query->where('nama_penilaian', 'ILIKE', '%' . $this->search . '%')
                    //->orWhere('pembelajaran.nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                    ->orWhereIn('pembelajaran_id', function($query){
                        $query->select('pembelajaran_id')
                        ->from('pembelajaran')
                        ->where('sekolah_id', session('sekolah_id'))
                        ->where('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                    });
            })->paginate($this->per_page),
            'breadcrumbs' => $breadcrumbs,
            'tombol_add' => 1,
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function updatedTingkat($value)
    {
        $this->reset(['rombongan_belajar_id', 'pembelajaran_id']);
        if($value){
            $this->data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->whereHas('pembelajaran', $this->kondisi());
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $this->data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId($value){
        if($value){
            $this->data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $this->data_pembelajaran]);
        }
    }
    public function updatedPembelajaranId($value){
        if($value){
            $this->data_bentuk_penilaian = Teknik_penilaian::where('kompetensi_id', $this->kompetensi_id)->get();
            $this->dispatchBrowserEvent('data_teknik', ['data_teknik' => $this->data_bentuk_penilaian]);
        }
    }
    public function updatedMetodeId($value){
        $this->reset(['bobot', 'show']);
        if($value){
            $pembelajaran = Pembelajaran::find($this->pembelajaran_id);
            $this->data_kd = Kompetensi_dasar::where(function($query) use ($pembelajaran){
                $query->where('mata_pelajaran_id', $pembelajaran->mata_pelajaran_id);
                $query->where('kompetensi_id', $this->kompetensi_id);
                $query->where('kelas_'.$this->tingkat, 1);
                $query->where('aktif', 1);
            })->orderBy('id_kompetensi')->get();
            if($this->data_kd->count()){
                $this->show = TRUE;
            }
            $bobot = Bobot_keterampilan::where('pembelajaran_id', $this->pembelajaran_id)->where('metode_id', $value)->first();
            if($bobot){
                $this->bobot = $bobot->bobot;
                $this->readonly = 'readonly';
            }
        }
    }
    private function kondisi(){
        return function($query){
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->where('guru_id', $this->loggedUser()->guru_id);
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            });
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            });
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
        };
    }
    public function store(){
        $this->validate(
            [
                'bobot' => 'required|integer', // 1MB Max
            ],
            [
                'bobot.required' => 'Bobot tidak boleh kosong!',
                'bobot.integer' => 'Bobot harus berupa angka!',
            ]
        );
        foreach($this->nama_penilaian as $key => $nama_penilaian){
            if(isset($this->kd_select[$key])){
                Bobot_keterampilan::updateOrCreate(
                    [
                        'sekolah_id' => session('sekolah_id'),
                        'pembelajaran_id' => $this->pembelajaran_id,
                        'metode_id' => $this->metode_id,
                    ],
                    [
                        'bobot' => $this->bobot,
                        'last_sync' => now(),
                    ]
                );
                $Rencana_penilaian = Rencana_penilaian::create([
                    'sekolah_id' => session('sekolah_id'),
                    'pembelajaran_id' => $this->pembelajaran_id,
                    'kompetensi_id' => $this->kompetensi_id,
                    'nama_penilaian' => $nama_penilaian,
                    'metode_id' => $this->metode_id,
                    'bobot' => $this->bobot,
                    'keterangan' => ($this->keterangan_penilaian[$key]) ?? NULL,
                    'last_sync' => now(),
                ]);
                foreach($this->kd_select[$key] as $kd_select){
                    Kd_nilai::create([
                        'sekolah_id' => session('sekolah_id'),
                        'rencana_penilaian_id' => $Rencana_penilaian->rencana_penilaian_id,
                        'kompetensi_dasar_id' => collect(explode('|', $kd_select))->first(),
                        'id_kompetensi' => collect(explode('|', $kd_select))->last()
                    ]);
                }
            }
        }
        $this->close();
    }
    private function resetInputFields(){
        $this->reset(['semester_id', 'tingkat', 'rombongan_belajar_id', 'pembelajaran_id', 'nama_penilaian', 'bentuk_penilaian', 'bobot_penilaian', 'data_kd', 'kd_select', 'keterangan_penilaian', 'rencana']);
    }
    public function cancel(){
        $this->resetInputFields();
    }
    public function close()
    {
        $this->resetInputFields();
        $this->emit('close-modal');
        $this->resetPage();
    }
    public function getID($rencana_penilaian_id){
        $this->rencana = Rencana_penilaian::with(['kd_nilai'])->find($rencana_penilaian_id);
    }
    public function delete(){
        if($this->rencana){
            $this->rencana->delete();
            $this->close();
            $this->alert('info', 'Rencana Penilaian Keterampilan berhasil dihapus', [
                'position' => 'center',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        } else {
            $this->alert('info', 'Rencana Penilaian Keterampilan gagal dihapus!. Silahkan coba beberapa saat lagi', [
                'position' => 'center',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        }
    }
    public function duplikasi(){
        //
    }
}
