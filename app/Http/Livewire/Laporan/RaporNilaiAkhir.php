<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;

class RaporNilaiAkhir extends Component
{
    public $show = FALSE;
    public $collection = [];
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar = [];
    public $rombongan_belajar_id;
    public $data_siswa = [];
    public $merdeka;
    
    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.laporan.rapor-nilai-akhir', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Cetak Rapor Semester"]
            ]
        ]);
    }
    private function loggedUser(){
        return auth()->user();
    }
    private function check_walas($rombongan_belajar_id = NULL){
        if($rombongan_belajar_id){
            $rombongan_belajar = Rombongan_belajar::find($rombongan_belajar_id);
            if($rombongan_belajar->guru_id == $this->loggedUser()->guru_id){
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if($this->loggedUser()->hasRole('wali', session('semester_id'))){
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    public function mount(){
        if($this->loggedUser()->hasRole('waka', session('semester_id'))){
            $this->show = FALSE;
        } elseif($this->check_walas()){
            $this->show = TRUE;
            $this->data_siswa = pd_walas();
            $this->rombongan_belajar = Rombongan_belajar::with([
                'kurikulum'
                ])->where(function($query){
                    $query->where('jenis_rombel', 1);
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                })->first();
            $this->merdeka = Str::contains($this->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka');
        }
    }
    public function updatedTingkat(){
        $this->reset(['rombongan_belajar_id', 'data_siswa', 'show']);
        if($this->tingkat){
            $data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('jenis_rombel', 1);
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId(){
        $this->reset(['data_siswa', 'show']);
        $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->with(['anggota_rombel' => function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        }])->orderBy('nama')->get();
        $this->rombongan_belajar = Rombongan_belajar::with([
            'kurikulum'
        ])->find($this->rombongan_belajar_id);
        $this->merdeka = Str::contains($this->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka');
        $this->show = TRUE;
    }
}
