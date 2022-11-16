<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Jurusan_sp;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;

class NilaiUs extends Component
{
    use LivewireAlert;
    public $show = FALSE;
    public $semester_id;
    public $jurusan_sp_id;
    public $data_rombongan_belajar = [];
    public $rombongan_belajar_id;
    public $data_pembelajaran = [];
    public $pembelajaran_id;

    public function render()
    {
        $this->semester_id = session('semester_id');
        if($this->loggedUser()->hasRole('wali', session('semester_id'))){
            $this->data_pembelajaran = Pembelajaran::whereHas('rombongan_belajar', function ($query) {
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('semester_id', session('semester_aktif'));
                $query->where('tingkat', 13);
                $query->where('guru_id', $this->loggedUser()->guru_id);
                $query->orWhere('sekolah_id', session('sekolah_id'));
                $query->where('semester_id', session('semester_aktif'));
                $query->where('tingkat', 12);
                $query->where('guru_id', $this->loggedUser()->guru_id);
            })->get();
        }
        return view('livewire.laporan.nilai-us', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Nilai US/USBN"]
            ],
            'jurusan_sp' => Jurusan_sp::whereHas('rombongan_belajar', function ($query) {
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('semester_id', session('semester_aktif'));
                $query->where('tingkat', 13);
                $query->orWhere('sekolah_id', session('sekolah_id'));
                $query->where('semester_id', session('semester_aktif'));
                $query->where('tingkat', 12);
            })->get(),
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function changeJurusan(){
        $this->reset(['data_rombongan_belajar', 'rombongan_belajar_id', 'data_pembelajaran', 'pembelajaran_id']);
        $this->data_rombongan_belajar = Rombongan_belajar::where(function($query){
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('semester_id', session('semester_aktif'));
            $query->where('jurusan_sp_id', $this->jurusan_sp_id);
        })->get();
    }
    public function changeRombel(){
        $this->reset(['data_pembelajaran', 'pembelajaran_id']);
        $this->data_pembelajaran = Pembelajaran::where(function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
        })->orderBy('mata_pelajaran_id', 'asc')->get();
    }
    public function changePembelajaran(){
        $this->alert('success', 'Basic Alert');
    }
}
