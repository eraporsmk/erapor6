<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Pembelajaran;
use App\Models\Rombongan_belajar;

class RaporNilaiAkhir extends Component
{
    public $show;
    public $collection = [];
    public $tingkat;
    public $rombongan_belajar = [];
    public $rombongan_belajar_id;
    public $data_siswa = [];
    
    public function render()
    {
        return view('livewire.laporan.rapor-nilai-akhir', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Cetak Rapor Semester"]
            ]
        ]);
    }
    public function mount(){
        if(check_walas()){
            $this->show = TRUE;
            $this->data_siswa = pd_walas();
        }
    }
}
