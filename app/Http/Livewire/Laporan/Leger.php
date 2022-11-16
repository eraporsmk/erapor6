<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;

class Leger extends Component
{
    public $rombongan_belajar_id;

    public function render()
    {
        $this->rombongan_belajar_id = (auth()->user()->guru->rombongan_belajar) ? auth()->user()->guru->rombongan_belajar->rombongan_belajar_id : NULL;
        return view('livewire.laporan.leger', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Unduh Leger"]
            ]
        ]);
    }
}
