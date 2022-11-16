<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Pembelajaran;
use App\Models\Rombongan_belajar;

class RaporSemester extends Component
{
    public $show;
    public $collection = [];
    public $tingkat;
    public $rombongan_belajar_id;
    public $data_siswa = [];
    
    public function render()
    {
        return view('livewire.laporan.rapor-semester', [
            'rombongan_belajar' => (check_walas()) ? Rombongan_belajar::with([
                'kurikulum'
                ])->where(function($query){
                    $query->where('jenis_rombel', 1);
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                })->first() : NULL,
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Cetak Rapor Semester"]
            ]
        ]);
    }
    private function loggedUser(){
        return auth()->user();
    }
    public function mount(){
        if(check_walas()){
            $this->show = TRUE;
            $this->data_siswa = pd_walas();
        }
    }
}
