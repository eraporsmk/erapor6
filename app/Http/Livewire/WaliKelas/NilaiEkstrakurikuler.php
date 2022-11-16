<?php

namespace App\Http\Livewire\WaliKelas;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Peserta_didik;
use App\Models\Nilai_ekstrakurikuler;
use App\Models\Rombongan_belajar;

class NilaiEkstrakurikuler extends Component
{
    use LivewireAlert;
    public $show = FALSE;
    public $nilai_ekskul = [];
    public $deskripsi_ekskul = [];
    public $nilai_satuan;
    public $ekstrakurikuler;
    
    public function render()
    {
        if($this->check_walas()){
            $this->show = TRUE;
        }
        return view('livewire.wali-kelas.nilai-ekstrakurikuler', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Nilai Ekstrakurikuler"]
            ]
        ]);
    }
    public function mount(){
        if($this->check_walas()){
            $this->getPd();
        }
    }
    public function updatedNilaiEkskul($value){
        $this->nilai_satuan = $value;
    }
    public function changeNilai($anggota_rombel_id, $rombongan_belajar_id){
        $this->getPd();
        $nilai = [
            1 => 'Sangat Baik',
            2 => 'Baik',
            3 => 'Cukup',
            4 => 'Kurang',
        ];
        $this->deskripsi_ekskul[$anggota_rombel_id][$rombongan_belajar_id] = '';
        if($this->nilai_satuan){
            $find = Rombongan_belajar::find($rombongan_belajar_id);
            $nama_ekskul = $find->nama;
            $this->deskripsi_ekskul[$anggota_rombel_id][$rombongan_belajar_id] = 'Melaksanakan kegiatan '.$nama_ekskul.' dengan '.$nilai[$this->nilai_satuan];
            $this->nilai_ekskul[$anggota_rombel_id][$rombongan_belajar_id] = $this->nilai_satuan;
        }
        //dump($this->nilai_satuan);
        //dd($this->nilai_ekskul);
    }
    private function getPd(){
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
        foreach($this->data_siswa as $siswa){
            foreach($siswa->anggota_rombel->anggota_ekskul as $anggota_ekskul){
                $this->nilai_ekskul[$siswa->anggota_rombel->anggota_rombel_id][$anggota_ekskul->rombongan_belajar->rombongan_belajar_id] = ($anggota_ekskul->single_nilai_ekstrakurikuler) ? $anggota_ekskul->single_nilai_ekstrakurikuler->nilai : '';
                $this->deskripsi_ekskul[$siswa->anggota_rombel->anggota_rombel_id][$anggota_ekskul->rombongan_belajar->rombongan_belajar_id] = ($anggota_ekskul->single_nilai_ekstrakurikuler) ? $anggota_ekskul->single_nilai_ekstrakurikuler->deskripsi_ekskul : '';
            }
            /*if($siswa->anggota_rombel->single_nilai_ekstrakurikuler){
                $this->nilai_ekskul[$siswa->anggota_rombel->anggota_rombel_id] = $siswa->anggota_rombel->single_nilai_ekstrakurikuler->nilai;
                $this->deskripsi_ekskul[$siswa->anggota_rombel->anggota_rombel_id] = $siswa->anggota_rombel->single_nilai_ekstrakurikuler->deskripsi_ekskul;
            }*/
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
