<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class Tamu extends Component
{
    public function render()
    {
        return view('livewire.dashboard.tamu', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"]
            ]
        ]);
    }
}
