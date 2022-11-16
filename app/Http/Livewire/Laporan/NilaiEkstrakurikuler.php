<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Peserta_didik;
use App\Models\Nilai_ekstrakurikuler;

class NilaiEkstrakurikuler extends Component
{
    use LivewireAlert;
    public $show = FALSE;

    public function render()
    {
        if($this->check_walas()){
            $this->show = TRUE;
        }
        return view('livewire.laporan.nilai-ekstrakurikuler', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Nilai Ekstrakurikuler"]
            ]
        ]);
    }
    public function mount(){
        if($this->check_walas()){
            $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                });
                $query->whereHas('anggota_ekskul', function($query){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('jenis_rombel', 51);
                    });
                });
            })->with(['anggota_rombel' => function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                });
                $query->with(['anggota_ekskul' => function($query){
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('jenis_rombel', 51);
                    });
                    $query->with([
                        'rombongan_belajar' => function($query){
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
}
