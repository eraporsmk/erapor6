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
    public $collection = [];
    public $rombongan_belajar_id;
    public $rencana_penilaian = [];
    
    protected $listeners = [
        'confirmed' => '$refresh'
    ];

    public function render()
    {
        return view('livewire.laporan.rapor-uts', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Cetak Rapor UTS"]
            ],
        ]);
    }
    public function mount(){
        $this->rombongan_belajar_id = $this->getRombel()->rombongan_belajar_id;
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
    }
    public function loggedUser(){
        return auth()->user();
    }
    private function check_walas(){
        if($this->loggedUser()->hasRole('wali', session('semester_id'))){
            return TRUE;
        } else {
            return FALSE;
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
    public function store(){
        $pembelajaran_id_array = [];
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
        $this->alert('success', 'Rapor UTS berhasil disimpan', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed' 
        ]);
    }
}
