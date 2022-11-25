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
        //$server = 'http://jembatan.test/api';
        $server = 'http://api.erapor-smk.net/api';
        $response = Http::withBasicAuth(config('erapor.user_erapor'), config('erapor.pass_erapor'))->post($server.'/dapodik/version');
        if($response->status() == 200){
            $version = $response->object();
            if (version_compare($version->version, config('global.app_version')) > 0) {
                $this->tersedia = TRUE;
            }
        }
    }
}
