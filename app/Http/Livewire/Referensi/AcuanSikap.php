<?php

namespace App\Http\Livewire\Referensi;

use Livewire\Component;
use App\Models\Sikap;

class AcuanSikap extends Component
{
    public function render()
    {
        return view('livewire.referensi.acuan-sikap', [
            'all_sikap' => $query = Sikap::whereNull('sikap_induk')->with('sikap')->get(),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], 
                ['link' => '#', 'name' => 'Referensi'], 
                ['name' => "Data Acuan Sikap"]
            ],
        ]);
    }
}
