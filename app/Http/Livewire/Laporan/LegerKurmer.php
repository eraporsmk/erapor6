<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use App\Models\Peserta_didik;
use App\Models\Pembelajaran;

class LegerKurmer extends Component
{
    public $rombongan_belajar_id;
    public $data_siswa = [];
    public $data_pembelajaran = [];

    public function render()
    {
        $rombel = $this->loggedUser()->guru->rombongan_belajar;
        $this->rombongan_belajar_id = ($rombel) ? $this->loggedUser()->guru->rombongan_belajar->rombongan_belajar_id : NULL;
        $link = ($rombel) ? route('unduhan.unduh-leger-nilai-kurmer', ['rombongan_belajar_id' => $this->rombongan_belajar_id]) : 'javascript:void(0)';
        return view('livewire.laporan.leger-kurmer', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Unduh Leger"]
            ],
            'tombol_add' => [
                'wire' => '',
                'link' => $link,
                'color' => 'success',
                'text' => 'Unduh Legger'
            ]
        ]);
    }
    public function mount(){
        //dd($this->rombongan_belajar_id);
        if($this->loggedUser()->guru->rombongan_belajar){
            $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->loggedUser()->guru->rombongan_belajar->rombongan_belajar_id);
            })->with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->loggedUser()->guru->rombongan_belajar->rombongan_belajar_id);
            }])->orderBy('nama')->get();
            $this->data_pembelajaran = Pembelajaran::where(function($query){
                $query->where('rombongan_belajar_id', $this->loggedUser()->guru->rombongan_belajar->rombongan_belajar_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
            })->orderBy('kelompok_id', 'asc')->orderBy('no_urut', 'asc')->get();
        }
    }
    private function loggedUser(){
        return auth()->user();
    }
}
