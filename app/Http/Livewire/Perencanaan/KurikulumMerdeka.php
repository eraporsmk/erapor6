<?php

namespace App\Http\Livewire\Perencanaan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Rencana_penilaian;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Capaian_penilaian;
use App\Models\Teknik_penilaian;
use App\Models\Tp_nilai;
use App\Models\Capaian_pembelajaran;
use App\Models\Tujuan_pembelajaran;

class KurikulumMerdeka extends Component
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
    public $nama = ' Penilaian Kurikulum Merdeka';
    public $data_rombongan_belajar;
    public $data_pembelajaran;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $pembelajaran_id;
    public $kompetensi_id = 4;
    public $data_kd = [];
    public $placeholder = 'Materi';
    public $data_bentuk_penilaian;
    public $nama_penilaian = [];
    public $bentuk_penilaian = [];
    public $bobot_penilaian = [];
    public $kd_select = [];
    public $keterangan_penilaian = [];
    public $rencana;
    public $show = FALSE;
    public $kompetensi_dasar_id;
    public $data_cp = [];
    public $data_tp = [];
    public $jml_tp = 0;
    public $cp_id;
    public $tp_id = [];
    public $mata_pelajaran_id;
    public $fase;
    public $rencana_penilaian_id;
    /*protected $rules = [
        'bentuk_penilaian.*' => 'required',
    ];
    protected $messages = [
        'bentuk_penilaian.*.required' => 'Teknik Penilaian tidak boleh kosong!!',
    ];*/
    protected $listeners = ['cancel'];
    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], 
            ['link' => '#', 'name' => 'Perencanaan'], 
            ['name' => "Penilaian Kurikulum Merdeka"]
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
                    $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
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
        return view('livewire.perencanaan.kurikulum-merdeka', [
            'collection' => Rencana_penilaian::with(['pembelajaran' => $callback, 'teknik_penilaian' => function($query){
                $query->select('teknik_penilaian_id', 'nama');
            }])->where(function($query) use ($callback){
                $query->where('kompetensi_id', $this->kompetensi_id);
                $query->whereHas('pembelajaran', $callback);
            })
            ->withCount('tp_nilai')
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
        $this->reset(['rombongan_belajar_id', 'pembelajaran_id', 'cp_id', 'show']);
        if($value){
            $this->data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->whereHas('pembelajaran', $this->kondisi());
                $query->where('jenis_rombel', 1);
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                });
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $this->data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId($value){
        $this->reset(['pembelajaran_id', 'cp_id', 'show']);
        if($value){
            $this->data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $this->data_pembelajaran]);
        }
    }
    public function updatedPembelajaranid($value){
        $this->reset(['cp_id', 'show']);
        if($value){
            $pembelajaran = Pembelajaran::find($this->pembelajaran_id);
            $this->mata_pelajaran_id = $pembelajaran->mata_pelajaran_id;
            //dd($this->mata_pelajaran_id);
            $this->fase = 'F';
            if($this->tingkat == 10){
                $this->fase = 'E';
            }
            $this->data_cp = Capaian_pembelajaran::where(function($query){
                $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                $query->where('fase', $this->fase);
            })->orderBy('cp_id')->get();
            $this->dispatchBrowserEvent('data_cp', ['data_cp' => $this->data_cp]);
        }
    }
    public function updatedCpId($value){
        $this->reset(['show']);
        if($value){
            $this->data_tp = Tujuan_pembelajaran::where('cp_id', $this->cp_id)->orderBy('created_at', 'asc')->get();
            $this->jml_tp = $this->data_tp->count();
            $this->show = TRUE;
            //$this->data_bentuk_penilaian = Teknik_penilaian::where('kompetensi_id', $this->kompetensi_id)->get();
            $this->dispatchBrowserEvent('tooltip');
            /*$pembelajaran = Pembelajaran::find($value);
            $this->data_kd = Kompetensi_dasar::where(function($query) use ($pembelajaran){
                $query->where('mata_pelajaran_id', $pembelajaran->mata_pelajaran_id);
                $query->where('kompetensi_id', $this->kompetensi_id);
                $query->where('kelas_'.$this->tingkat, 1);
                $query->where('aktif', 1);
            })->orderBy('id_kompetensi')->get();
            if($this->data_kd->count()){
                
            }
            */
        }
    }
    private function kondisi(){
        return function($query){
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->where('guru_id', $this->loggedUser()->guru_id);
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
        };
    }
    public function store(){
        //$pembelajaran = Pembelajaran::where('rombongan_belajar_id', $this->rombongan_belajar_id)->where('mata_pelajaran_id', $this->mata_pelajaran_id)->first();
        /*
        public $nama_penilaian = [];
        public $bentuk_penilaian = [];
        public $bobot_penilaian = [];
        public $kd_select = [];
        public $keterangan_penilaian = [];
        */
        $metode_penilaian = Teknik_penilaian::where('kompetensi_id', $this->kompetensi_id)->where('nama', 'Sumatif Lingkup Materi')->first();
        foreach($this->nama_penilaian as $key => $nama_penilaian){
            if(isset($this->tp_id[$key])){
                $keterangan_penilaian = NULL;
                if(isset($this->keterangan_penilaian[$key])){
                    $keterangan_penilaian = $this->keterangan_penilaian[$key];
                }
                $Rencana_penilaian = Rencana_penilaian::create([
                    'sekolah_id' => session('sekolah_id'),
                    'pembelajaran_id' => $this->pembelajaran_id,
                    'kompetensi_id' => $this->kompetensi_id,
                    'nama_penilaian' => $nama_penilaian,
                    'metode_id' => $metode_penilaian->teknik_penilaian_id,
                    'bobot' => 0,
                    'keterangan' => $keterangan_penilaian,
                    'last_sync' => now(),
                ]);
                foreach($this->tp_id[$key] as $tp_id => $selected){
                    if($selected){
                        Tp_nilai::create([
                            'sekolah_id' => session('sekolah_id'),
                            'rencana_penilaian_id' => $Rencana_penilaian->rencana_penilaian_id,
                            'cp_id' => $this->cp_id,
                            'tp_id' => $tp_id,
                            'kompeten' => 0,
                        ]);
                    }
                }
            }
            $this->show = FALSE;
        }
        $this->close();
    }
    private function resetInputFields(){
        $this->reset(['semester_id', 'tingkat', 'rombongan_belajar_id', 'mata_pelajaran_id', 'nama_penilaian', 'bentuk_penilaian', 'bobot_penilaian', 'data_kd', 'kd_select', 'keterangan_penilaian', 'rencana', 'tp_id']);
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
        $this->rencana_penilaian_id = $rencana_penilaian_id;
        $this->rencana = Rencana_penilaian::with(['tp.cp'])->find($this->rencana_penilaian_id);
    }
    public function delete(){
        $data = Rencana_penilaian::find($this->rencana_penilaian_id);
        $data->delete();
        $this->close();
        $this->alert('info', 'Rencana Penilaian PK berhasil dihapus', [
            'position' => 'center',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
    }
    public function duplikasi(){
        //
    }
}
