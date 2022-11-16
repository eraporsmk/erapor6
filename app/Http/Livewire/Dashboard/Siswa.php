<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class Siswa extends Component
{
    public function render()
    {
        return view('livewire.dashboard.siswa', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"]
            ],
            'username' => auth()->user()->name,
        ]);
    }
}
