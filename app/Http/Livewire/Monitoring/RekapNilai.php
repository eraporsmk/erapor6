<?php

namespace App\Http\Livewire\Monitoring;

use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Peserta_didik;

class RekapNilai extends Component
{
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $pembelajaran_id;
    public $nama_mapel;
    public $data_siswa = [];
    public $rasio_p;
    public $rasio_k;
    public $kkm;
    public $kelompok_id;
    public $mapel_produktif;
    public $show = FALSE;
    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.monitoring.rekap-nilai', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Monitoring'], ['name' => "Rekapitulasi Nilai"]
            ],
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function updatedTingkat($value){
        $this->reset(['show', 'data_siswa', 'rasio_p', 'rasio_k', 'kkm', 'kelompok_id', 'mapel_produktif']);
        if($this->tingkat){
            $data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->whereHas('pembelajaran', $this->kondisi());
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $data_rombongan_belajar]);
        }
    }
    private function kondisi(){
        return function($query){
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            });
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            if(!$this->loggedUser()->hasRole('waka', session('semester_id'))){
                $query->where('guru_id', $this->loggedUser()->guru_id);
                $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
                $query->whereHas('rombongan_belajar', function($query){
                    $query->whereHas('kurikulum', function($query){
                        $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                    });
                });
                if($this->rombongan_belajar_id){
                    $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                }
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
            }
        };
    }
    public function updatedRombonganBelajarId($value){
        if($this->rombongan_belajar_id){
            $data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $data_pembelajaran]);
        }
    }
    public function updatedPembelajaranId($value){
        if($value){
            $pembelajaran = Pembelajaran::find($value);
            $kelompok_produktif = array(4, 5, 9, 10, 13);
            $this->mapel_produktif = NULL;
            if(in_array($pembelajaran->kelompok_id, $kelompok_produktif)){
                $this->mapel_produktif = 1;
            }
            $this->rasio_p = $pembelajaran->rasio_p;
            $this->rasio_k = $pembelajaran->rasio_k;
            $this->kkm = $pembelajaran->kkm;
            $this->kelompok_id = $pembelajaran->kelompok_id;
            $this->nama_mapel = $pembelajaran->nama_mata_pelajaran;
            $this->show = TRUE;
            $this->data_siswa = Peserta_didik::with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with(['nilai_akhir_pengetahuan' => function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                }]);
                $query->with(['nilai_akhir_keterampilan' => function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                }]);
            }])->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            })->orderBy('nama')->get();
        }
    }
}
