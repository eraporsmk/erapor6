<?php

namespace App\Http\Livewire\Monitoring;

use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Peserta_didik;

class PrestasiIndividu extends Component
{
    public $show = FALSE;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $pembelajaran_id;
    public $siswa;
    public $data_siswa;
    public $skm;
    public $rerata_pengetahuan = 0;
    public $rerata_keterampilan = 0;
    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.monitoring.prestasi-individu', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Monitoring'], ['name' => "Prestasi Individu PD"]
            ]
        ]);
    }
    private function loggedUser(){
        return auth()->user();
    }
    public function updatedTingkat($value){
        $this->reset(['show', 'data_siswa', 'skm']);
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
            $query->where('guru_id', $this->loggedUser()->guru_id);
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            });
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
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
        };
    }
    public function updatedRombonganBelajarId($value){
        $this->reset(['show', 'data_siswa', 'skm']);
        if($this->rombongan_belajar_id){
            $data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $data_pembelajaran]);
        }
    }
    public function updatedPembelajaranId($value){
        $this->reset(['show', 'data_siswa', 'skm']);
        if($this->pembelajaran_id){
            $this->pembelajaran = Pembelajaran::with(['rombongan_belajar'])->find($this->pembelajaran_id);
            $data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->pembelajaran->rombongan_belajar->rombongan_belajar_id);
            })->with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->pembelajaran->rombongan_belajar->rombongan_belajar_id);
            }])->orderBy('nama')->get();
            $this->dispatchBrowserEvent('data_siswa', ['data_siswa' => $data_siswa]);
        }
    }
    public function updatedSiswa($value){
        $this->reset(['show', 'skm', 'rerata_pengetahuan', 'rerata_keterampilan']);
        if($this->siswa){
            $this->pembelajaran = Pembelajaran::with([
                'rombongan_belajar',
                'kd_nilai_p' => function($query){
                    $query->with('kompetensi_dasar');
                    $query->select(['kd_nilai.kompetensi_dasar_id', 'rencana_penilaian.kompetensi_id', 'rencana_penilaian.nama_penilaian']);
                    $query->groupBy(['kd_nilai.kompetensi_dasar_id', 'rencana_penilaian.kompetensi_id', 'rencana_penilaian.nama_penilaian', 'rencana_penilaian.pembelajaran_id']);
                    $query->orderBy('kd_nilai.kompetensi_dasar_id', 'asc');
                },
                'kd_nilai_k' => function($query){
                    $query->with('kompetensi_dasar');
                    $query->select(['kd_nilai.kompetensi_dasar_id', 'rencana_penilaian.kompetensi_id', 'rencana_penilaian.nama_penilaian']);
                    $query->groupBy(['kd_nilai.kompetensi_dasar_id', 'rencana_penilaian.kompetensi_id', 'rencana_penilaian.nama_penilaian', 'rencana_penilaian.pembelajaran_id']);
                    $query->orderBy('kd_nilai.kompetensi_dasar_id', 'asc');
                },
                'one_anggota_rombel' => function($query){
                    $query->with([
                        'peserta_didik',
                        'nilai_kd_pengetahuan' => function($query){
                            $query->where('pembelajaran_id', $this->pembelajaran_id);
                            $query->with(['kd_nilai']);
                        },
                        'nilai_kd_keterampilan' => function($query){
                            $query->where('pembelajaran_id', $this->pembelajaran_id);
                            $query->with(['kd_nilai']);
                        },
                    ]);
                    $query->where('anggota_rombel_id', $this->siswa);
                }
            ])->find($this->pembelajaran_id);
            $this->skm = get_kkm($this->pembelajaran->kelompok_id, 0);
            $this->show = TRUE;
            $nilai_kd_pengetahuan = [];
            $id_kompetensi_pengetahuan = [];
            $skm_p = [];
            foreach($this->pembelajaran->one_anggota_rombel->nilai_kd_pengetahuan as $nkp){
                $nilai_kd_pengetahuan[] = $nkp->nilai_kd;
                $id_kompetensi_pengetahuan[] = $nkp->kd_nilai->id_kompetensi;
                $skm_p[] = $this->skm;
            }
            $nilai_kd_keterampilan = [];
            $id_kompetensi_keterampilan = [];
            $skm_k = [];
            foreach($this->pembelajaran->one_anggota_rombel->nilai_kd_keterampilan as $nkk){
                $nilai_kd_keterampilan[] = $nkk->nilai_kd;
                $id_kompetensi_keterampilan[] = $nkk->kd_nilai->id_kompetensi;
                $skm_k[] = $this->skm;
            }
            $this->rerata_pengetahuan = $this->pembelajaran->one_anggota_rombel->nilai_kd_pengetahuan->avg('nilai_kd');
            $this->rerata_keterampilan = $this->pembelajaran->one_anggota_rombel->nilai_kd_keterampilan->avg('nilai_kd');
            $this->dispatchBrowserEvent('chart', ['chart' => [
                'id_kompetensi_pengetahuan' => $id_kompetensi_pengetahuan,
                'id_kompetensi_keterampilan' => $id_kompetensi_keterampilan,
                'nilai_kd_pengetahuan' => $nilai_kd_pengetahuan,
                'nilai_kd_keterampilan' => $nilai_kd_keterampilan,
                'skm_p' => $skm_p,
                'skm_k' => $skm_k,
            ]]);
        }
    }
}
