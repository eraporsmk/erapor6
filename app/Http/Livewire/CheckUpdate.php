<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class CheckUpdate extends Component
{
    public $tersedia = FALSE;
    public function render()
    {
        return view('livewire.check-update', [
            'os' => strtoupper(substr(PHP_OS, 0, 3)),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['name' => "Cek Pembaharuan"]
            ],
        ]);
    }
    public function mount(){
        $response = Http::post('http://api.erapor-smk.net/api/version');
        if($response->successful()){
            $version = $response->object();
            if (version_compare($version->version, config('global.app_version')) > 0) {
                $this->tersedia = TRUE;
            }
        }
    }
}
