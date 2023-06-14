<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Nilai_ekstrakurikuler;

class NilaiEkstrakurikuler extends Component
{
    use LivewireAlert;
    public $tingkat;
    public $rombongan_belajar_id;
    public $show = FALSE;
    public $semester_id;
    public $data_rombongan_belajar = [];
    public $data_siswa = [];

    public function render()
    {
        $this->semester_id = session('semester_id');
        if($this->check_walas()){
            $this->show = TRUE;
        }
        return view('livewire.laporan.nilai-ekstrakurikuler', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Nilai Ekstrakurikuler"]
            ]
        ]);
    }
    public function kondisi(){
        return function($query){
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('semester_id', session('semester_aktif'));
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            } else {
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 1);
                });
            }
            //$query->with(['rombongan_belajar']);
        };
    }
    public function mount(){
        if($this->check_walas()){
            $this->rombongan_belajar_id = $this->loggedUser()->guru->rombongan_belajar->rombongan_belajar_id;
            $this->getPd();
        }
    }
    public function loggedUser(){
        return auth()->user();
    }
    private function check_walas(){
        if($this->loggedUser()->hasRole('wali', session('semester_id'))){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    private function getPd(){
        $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('semester_id', session('semester_aktif'));
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            } else {
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 1);
                });
            }
        })->with(['anggota_rombel' => function($query){
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('semester_id', session('semester_aktif'));
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            } else {
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 1);
                });
            }
            $query->with(['anggota_ekskul' => function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('jenis_rombel', 51);
                });
                $query->with([
                    'rombongan_belajar' => function($query){
                        $query->where('sekolah_id', session('sekolah_id'));
                        $query->where('semester_id', session('semester_aktif'));
                        $query->where('jenis_rombel', 51);
                        $query->with(['kelas_ekskul' => function($query){
                            $query->with('guru');
                        }]);
                    },
                    'single_nilai_ekstrakurikuler'
                ]);
            }]);
        }])->orderBy('nama')->get();
    }
    public function updatedTingkat(){
        $this->reset(['data_rombongan_belajar', 'rombongan_belajar_id', 'data_siswa', 'show']);
        if($this->tingkat){
            $data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId(){
        $this->reset(['data_siswa', 'show']);
        $this->show = TRUE;
        $this->getPd();
    }
}
