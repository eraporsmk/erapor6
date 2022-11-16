<?php

namespace App\Http\Livewire\Monitoring;

use Livewire\Component;

class PrestasiIndividu extends Component
{
    public function render()
    {
        return view('livewire.monitoring.prestasi-individu', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Monitoring'], ['name' => "Prestasi Individu PD"]
            ]
        ]);
    }
}
