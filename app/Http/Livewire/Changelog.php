<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Changelog extends Component
{
    public function render()
    {
        return view('livewire.changelog', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['name' => "Daftar Perubahan"]
            ],
        ]);
    }
}
