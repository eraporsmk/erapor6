<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Pembelajaran;
use App\Models\Rombongan_belajar;
use App\Models\Anggota_rombel;

class RaporSemester extends Component
{
    public $show;
    public $collection = [];
    public $tingkat;
    public $rombongan_belajar_id;
    public $data_siswa = [];
    public $get_siswa;

    public function render()
    {
        return view('livewire.laporan.rapor-semester', [
            'rombongan_belajar' => (check_walas()) ? Rombongan_belajar::with([
                'kurikulum'
                ])->where(function($query){
                    $query->where('jenis_rombel', 1);
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                })->first() : NULL,
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Cetak Rapor Semester"]
            ]
        ]);
    }
    private function loggedUser(){
        return auth()->user();
    }
    public function mount(){
        if(check_walas()){
            $this->show = TRUE;
            $this->data_siswa = pd_walas();
        }
    }
    public function preview($anggota_rombel_id){
        $this->reset(['get_siswa']);
        $this->anggota_rombel_id = $anggota_rombel_id;
        $this->get_siswa = Anggota_rombel::with([
            'peserta_didik' => function($query){
                $query->with(['agama', 'wilayah', 'pekerjaan_ayah', 'pekerjaan_ibu', 'pekerjaan_wali', 'sekolah' => function($q){
                    $q->with('kepala_sekolah');
                }]);
            },
            'rombongan_belajar' => function($query){
                $query->where('jenis_rombel', 1);
                $query->with([
                    'pembelajaran' => function($query){
                        $callback = function($query){
                            $query->where('anggota_rombel_id', request()->route('anggota_rombel_id'));
                        };
                        $query->with([
                            'kelompok',
                            'nilai_akhir_pengetahuan' => $callback,
                            'nilai_akhir_keterampilan' => $callback,
                            'nilai_akhir_pk' => $callback,
                            'deskripsi_mata_pelajaran' => $callback,
                        ]);
                        $query->whereNotNull('kelompok_id');
                        $query->orderBy('kelompok_id', 'asc');
                        $query->orderBy('no_urut', 'asc');
                    },
                    'jurusan',
                    'kurikulum',
                    'wali_kelas'
                ]);
            },
            'single_catatan_ppk' => function($query){
                $query->with(['nilai_karakter' => function($query){
                    $query->with('sikap');
                }]);
            },
            'kenaikan', 
            'all_nilai_ekskul' => function($query){
                $query->whereHas('ekstrakurikuler', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                });
                $query->with(['ekstrakurikuler']);
            },
            'kehadiran',
            'all_prakerin',
            'single_catatan_wali'
        ])->find($this->anggota_rombel_id);
        $this->emit('preview-nilai');
    }
}
