<?php

namespace App\Http\Livewire\Perencanaan;

use Livewire\Component;
use App\Models\Pembelajaran;

class RasioNilaiAkhir extends Component
{
    
    public $collection;
    public $rasio_p = [];
    public $rasio_k = [];
    public function mount(){
        $pembelajaran = Pembelajaran::where(function($query){
            $query->where('guru_id', $this->loggedUser()->guru_id);
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            });
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('semester_id', session('semester_aktif'));
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            });
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('semester_id', session('semester_aktif'));
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
        })->get();
        $this->collection = $pembelajaran;
        foreach($pembelajaran as $rasio){
            $this->rasio_p[$rasio->pembelajaran_id] = $rasio->rasio_p;
            $this->rasio_k[$rasio->pembelajaran_id] = $rasio->rasio_k;
        }
    }
    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Perencanaan'], ['name' => "Rasio Nilai Akhir"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        return view('livewire.perencanaan.rasio-nilai-akhir', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function save(){
        foreach($this->rasio_p as $pembelajaran_id => $rasio_p){
            $pembelajaran = Pembelajaran::find($pembelajaran_id);
            if(isset($this->rasio_k[$pembelajaran_id])){
                if($rasio_p + $this->rasio_k[$pembelajaran_id] != 100){
                    $this->toastr('error', $pembelajaran->nama_mata_pelajaran, 'Pastikan jumlah rasio nilai pengetahuan dan rasio nilai keterampilan sama dengan 100 (seratus)');
                } else {
                    $pembelajaran->rasio_p = $rasio_p;
                    $pembelajaran->rasio_k = $this->rasio_k[$pembelajaran_id];
                    if($pembelajaran->save()){
                        $this->toastr('success', $pembelajaran->nama_mata_pelajaran, 'Rasio Nilai Akhir berhasil disimpan');
                    } else {
                        $this->toastr('warning', $pembelajaran->nama_mata_pelajaran, 'Silahkan coba beberapa saat lagi!');
                    }
                }
            } else {
                $this->toastr('warning', $pembelajaran->nama_mata_pelajaran, 'Formulir harus terisi');
            }
        }
    }
    public function toastr($type, $title, $message){
        $this->dispatchBrowserEvent('toastr', ['type' => $type,  'title' => $title, 'message' => $message]);
    }
}
