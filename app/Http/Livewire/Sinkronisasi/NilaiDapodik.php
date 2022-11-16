<?php

namespace App\Http\Livewire\Sinkronisasi;

use Livewire\Component;

class NilaiDapodik extends Component
{
    public function render()
    {
        return view('livewire.sinkronisasi.nilai-dapodik', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Sinkronisasi'], ['name' => 'Kirim Nilai Dapodik']
            ]
        ]);
    }
}
