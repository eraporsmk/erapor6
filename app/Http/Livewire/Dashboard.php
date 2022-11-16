<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sekolah;

class Dashboard extends Component
{
    public $lat = '';
    public $lng = '';
    public $jarak;
    protected $listeners = ['postAdded' => 'incrementPostCount'];
    public function render()
    {
        $user = auth()->user();
        if($user->sekolah_id){
            $sekolah = Sekolah::find($user->sekolah_id);
            $this->lat = ($sekolah->lintang) ? $sekolah->lintang : config('laravel-maps.map_center.lat');
            $this->lng = ($sekolah->bujur) ? $sekolah->bujur : config('laravel-maps.map_center.lng');
        }
        return view('livewire.dashboard');
    }
    public function incrementPostCount($data){
        return session(['jarak' => $data]);
    }
}
