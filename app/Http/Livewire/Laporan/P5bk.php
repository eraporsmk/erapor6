<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;

class P5bk extends Component
{
    public function render()
    {
        return view('livewire.laporan.p5bk', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Cetak Rapor P5BK"]
            ]
        ]);
    }
}
