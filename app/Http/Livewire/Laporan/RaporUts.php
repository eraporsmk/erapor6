<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Pembelajaran;
use App\Models\Rombongan_belajar;
use App\Models\Rapor_pts;

class RaporUts extends Component
{
    use LivewireAlert;
    public $show = FALSE;
    public $collection = [];
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $rencana_penilaian = [];
    public $data_rombongan_belajar = [];

    protected $listeners = [
        'confirmed' => '$refresh'
    ];

    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.laporan.rapor-uts', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Cetak Rapor UTS"]
            ],
        ]);
    }
    public function mount(){
        $this->rombongan_belajar_id = ($this->getRombel()) ? $this->getRombel()->rombongan_belajar_id : NULL;
        $this->collection = Pembelajaran::with([
            'guru', 
            'rencana_penilaian' => function($query) {
                $query->where('kompetensi_id', 1);
            },
            'rapor_pts',
            'rombongan_belajar',
        ])->whereNotNull('kelompok_id')->whereNotNull('no_urut')->whereHas('rombongan_belajar', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->orderBy('kelompok_id', 'asc')->get();
        foreach($this->collection as $item){
            $this->rencana_penilaian[$item->pembelajaran_id] = ($item->rapor_pts->count()) ? $item->rapor_pts->pluck('rencana_penilaian_id')->toArray() : '';
        }
        if($this->loggedUser()->hasRole('waka', session('semester_id'))){
            $this->show = FALSE;
        } elseif($this->check_walas()){
            $this->show = TRUE;
        }
        //$this->show = $this->check_walas($this->rombongan_belajar_id);
    }
    public function loggedUser(){
        return auth()->user();
    }
    private function check_walas($rombongan_belajar_id = NULL){
        if($rombongan_belajar_id){
            $rombongan_belajar = Rombongan_belajar::find($rombongan_belajar_id);
            if($rombongan_belajar->guru_id == $this->loggedUser()->guru_id){
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if($this->loggedUser()->hasRole('wali', session('semester_id'))){
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    public function getRombel(){
        return Rombongan_belajar::where(function($query){
            $query->where('guru_id', $this->loggedUser()->guru_id);
			$query->where('jenis_rombel', 1);
			$query->where('semester_id', session('semester_aktif'));
            $query->where('sekolah_id', session('sekolah_id'));
        })->first();
    }
    public function updatedTingkat(){
        $this->reset(['data_rombongan_belajar', 'rombongan_belajar_id', 'collection', 'rencana_penilaian']);
        if($this->tingkat){
            $data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('jenis_rombel', 1);
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId(){
        $this->reset(['collection', 'rencana_penilaian']);
        $this->collection = Pembelajaran::with([
            'guru', 
            'rencana_penilaian' => function($query) {
                $query->where('kompetensi_id', 1);
            },
            'rapor_pts',
            'rombongan_belajar',
        ])->whereNotNull('kelompok_id')->whereNotNull('no_urut')->whereHas('rombongan_belajar', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->orderBy('kelompok_id', 'asc')->get();
        $pembelajaran_id = [];
        $this->rencana_penilaian = [];
        foreach($this->collection as $item){
            $this->rencana_penilaian[$item->pembelajaran_id] = ($item->rapor_pts->count()) ? $item->rapor_pts->pluck('rencana_penilaian_id')->toArray() : '';
            $pembelajaran_id[] = $item->pembelajaran_id;
        }
        $this->dispatchBrowserEvent('rencana_penilaian', [
            'pembelajaran_id' => $pembelajaran_id,
            'rencana_penilaian' => $this->collection,
            'rencana_penilaian_select' => $this->rencana_penilaian,
        ]);
        $this->dispatchBrowserEvent('pharaonic.select2.init');
        /*$this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->with(['anggota_rombel' => function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        }])->orderBy('nama')->get();
        $this->rombongan_belajar = Rombongan_belajar::with([
            'kurikulum'
        ])->find($this->rombongan_belajar_id);*/
        $this->show = TRUE;
    }
    public function store(){
        $pembelajaran_id_array = [];
        $rencana_penilaian_id_array = [];
        foreach($this->rencana_penilaian as $pembelajaran_id => $rencana_penilaian){
            $pembelajaran_id_array[] = $pembelajaran_id;
            if($rencana_penilaian){
                foreach($rencana_penilaian as $rencana){
                    $rencana_penilaian_id_array[] = $rencana;
                    Rapor_pts::updateOrCreate(
                        [
                            'rombongan_belajar_id' => $this->rombongan_belajar_id,
                            'pembelajaran_id' => $pembelajaran_id,
                            'rencana_penilaian_id' => $rencana
                        ],
                        [
                            'sekolah_id' => session('sekolah_id'),
                            'last_sync'	=> now(),
                        ]
                    );
                }
            }
        }
        Rapor_pts::where(function ($query) use ($pembelajaran_id_array, $rencana_penilaian_id_array){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            $query->whereIn('pembelajaran_id', $pembelajaran_id_array);
            $query->whereNotIn('rencana_penilaian_id', $rencana_penilaian_id_array);
        })->delete();
        if($rencana_penilaian_id_array){
            $this->alert('success', 'Rapor UTS berhasil disimpan', [
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'confirmed' 
            ]);
        } else {
            $this->alert('error', 'Tidak ada Rapor UTS disimpan', [
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'confirmed' 
            ]);
        }
    }
}
