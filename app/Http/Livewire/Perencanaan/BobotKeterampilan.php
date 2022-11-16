<?php

namespace App\Http\Livewire\Perencanaan;

use Livewire\Component;
use App\Models\Bobot_keterampilan;
use App\Models\Rencana_penilaian;

class BobotKeterampilan extends Component
{
    public $collection;
    public $bobot = [];
    public $pembelajaran_id = [];

    public function mount(){
        $callback = function($query){
			$query->where('pembelajaran.sekolah_id', session('sekolah_id'));
			$query->where('pembelajaran.semester_id', session('semester_aktif'));
			$query->where('pembelajaran.guru_id', $this->loggedUser()->guru_id);
			$query->whereNotNull('kelompok_id');
			$query->whereNotNull('no_urut');
			$query->orWhere('pembelajaran.sekolah_id', session('sekolah_id'));
			$query->where('pembelajaran.semester_id', session('semester_aktif'));
			$query->where('pembelajaran.guru_pengajar_id', $this->loggedUser()->guru_id);
			$query->whereNotNull('kelompok_id');
			$query->whereNotNull('no_urut');
		};
		$bobot_keterampilan = Bobot_keterampilan::whereHas('pembelajaran', $callback)->with(['metode'])->with(['pembelajaran', 'pembelajaran.rombongan_belajar'])->get();
        $this->collection = $bobot_keterampilan;
        foreach($bobot_keterampilan as $bobot){
            $this->bobot[$bobot->bobot_keterampilan_id] = $bobot->bobot;
            $this->pembelajaran_id[$bobot->bobot_keterampilan_id] = $bobot->pembelajaran->pembelajaran_id;
        }
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Perencanaan'], ['name' => "Penentuan Bobot Penilaian Keterampilan"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        return view('livewire.perencanaan.bobot-keterampilan', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    public function save(){
        foreach($this->bobot as $bobot_keterampilan_id => $bobot){
            $bobot_keterampilan = Bobot_keterampilan::find($bobot_keterampilan_id);
            $bobot_keterampilan->bobot = $bobot;
            if($bobot_keterampilan->save()){
                Rencana_penilaian::where('pembelajaran_id', $this->pembelajaran_id[$bobot_keterampilan_id])->where('metode_id', $bobot_keterampilan->metode_id)->update(['bobot' => $bobot]);
                $this->toastr('success', 'Berhasil', 'Bobot Keterampilan berhasil disimpan');
            } else {
                $this->toastr('warning', 'Gagal', 'Silahkan coba beberapa saat lagi!');
            }
        }
    }
    public function toastr($type, $title, $message){
        $this->dispatchBrowserEvent('toastr', ['type' => $type,  'title' => $title, 'message' => $message]);
    }
}
