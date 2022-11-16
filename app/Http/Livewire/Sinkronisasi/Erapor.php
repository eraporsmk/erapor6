<?php

namespace App\Http\Livewire\Sinkronisasi;

use Livewire\Component;

class Erapor extends Component
{
    public function render()
    {
        return view('livewire.sinkronisasi.erapor', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Sinkronisasi'], ['name' => 'Kirim Data e-Rapor']
            ]
        ]);
    }
}
