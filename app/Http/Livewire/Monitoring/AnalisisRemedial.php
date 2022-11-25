<?php

namespace App\Http\Livewire\Monitoring;

use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Peserta_didik;

class AnalisisRemedial extends Component
{
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $pembelajaran_id;
    public $nama_rombel;
    public $nama_mapel;
    public $nama_rencana;
    public $bobot;
    public $data_siswa = [];
    public $kkm;
    public $show = FALSE;
    public $kompetensi_id;
    public $rencana_penilaian_id;
    public $nilai_value = [];
    public $data_kd = [];
    public $pembelajaran;
    public $with;
    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.monitoring.analisis-remedial', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Monitoring'], ['name' => "Analisis Hasil Remedial"]
            ]
        ]);
    }
    private function loggedUser(){
        return auth()->user();
    }
    public function updatedTingkat($value){
        $this->reset(['show', 'data_siswa', 'kkm']);
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
        $this->reset(['show', 'data_siswa', 'kkm']);
        if($this->rombongan_belajar_id){
            $data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $data_pembelajaran]);
        }
    }
    public function updatedPembelajaranId($value){
        $this->reset(['show', 'data_siswa', 'kkm']);
        if($this->pembelajaran_id){
            $pembelajaran = Pembelajaran::with(['rombongan_belajar'])->find($this->pembelajaran_id);
            $this->nama_mapel = $pembelajaran->nama_mata_pelajaran;
            $this->nama_rombel = $pembelajaran->rombongan_belajar->nama;
            $this->kkm = get_kkm($pembelajaran->kelompok_id, 0);
            $data_kompetensi = [
                ['id' => 1, 'nama' => 'Pengetahuan'],
                ['id' => 2, 'nama' => 'Keterampilan'],
                //['id' => 3, 'nama' => 'Pusat Keunggulan'],
            ];
            $this->dispatchBrowserEvent('data_kompetensi', ['data_kompetensi' => $data_kompetensi]);
        }
    }
    public function updatedKompetensiId($value){
        $this->reset(['show', 'data_siswa', 'kkm', 'data_kd']);
        if($this->kompetensi_id){
            $kelompok_produktif = array(4, 5, 9, 10, 13);
            $mapel_produktif = NULL;
            /*$data_rencana = Rencana_penilaian::where(function($query){
                $query->where('pembelajaran_id', $this->pembelajaran_id);
                $query->where('kompetensi_id', $this->kompetensi_id);
            })->orderBy('nama_penilaian')->get();
            $this->dispatchBrowserEvent('data_rencana', ['data_rencana' => $data_rencana]);*/
            $nama_kompetensi = ($this->kompetensi_id == 1) ? 'p' : 'k';
            $this->with = ($this->kompetensi_id == 1) ? 'v_nilai_akhir_p' : 'v_nilai_akhir_k';
            $get_mapel_agama = filter_agama_siswa($this->pembelajaran_id, $this->rombongan_belajar_id);
            $this->pembelajaran = Pembelajaran::with(['kd_nilai' => function($query) use ($nama_kompetensi, $get_mapel_agama){
                $query->with('kompetensi_dasar');
                $query->select(['kd_nilai.kompetensi_dasar_id', 'rencana_penilaian.kompetensi_id']);
                $query->where('rencana_penilaian.kompetensi_id', $this->kompetensi_id);
                $query->groupBy(['kd_nilai.kompetensi_dasar_id', 'rencana_penilaian.kompetensi_id', 'rencana_penilaian.pembelajaran_id']);
                $query->orderBy('kd_nilai.kompetensi_dasar_id', 'asc');
            }, 'anggota_rombel' => function($query) use ($nama_kompetensi, $get_mapel_agama){
                if($get_mapel_agama){
                    $query->whereHas('peserta_didik', function($query) use ($get_mapel_agama){
                        $query->where('agama_id', $get_mapel_agama);
                    });
                }
                $query->with([
                    'peserta_didik' => function($query) use ($get_mapel_agama){
                        if($get_mapel_agama){
                            $query->where('agama_id', $get_mapel_agama);
                        } 
                    },
                    'v_nilai_akhir_'.$nama_kompetensi => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    'nilai_remedial' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                        $query->where('kompetensi_id', $this->kompetensi_id);
                    }
                ]);
            }])->find($this->pembelajaran_id);
            if(in_array($this->pembelajaran->kelompok_id, $kelompok_produktif)){
                $mapel_produktif = 1;
            }
            if($this->pembelajaran->kd_nilai->count()){
                foreach($this->pembelajaran->kd_nilai as $kompetensi_dasar){
                    $this->data_kd[$kompetensi_dasar->kompetensi_dasar->id_kompetensi] = $kompetensi_dasar->kompetensi_dasar;
                }
                ksort($this->data_kd);
            }
            $this->show = TRUE;
        }
    }
    public function updatedRencanaPenilaianId(){
        $this->reset(['show', 'data_siswa', 'kkm']);
        $pembelajaran = Pembelajaran::with(['kd_nilai' => function($q) use ($request, $nama_kompetensi){
			$q->with('kompetensi_dasar');
			$q->select(['kd_nilai.kompetensi_dasar_id', 'rencana_penilaian.kompetensi_id']);
			$q->where('rencana_penilaian.kompetensi_id', $request['kompetensi_id']);
			$q->groupBy(['kd_nilai.kompetensi_dasar_id', 'rencana_penilaian.kompetensi_id', 'rencana_penilaian.pembelajaran_id']);
			$q->orderBy('kd_nilai.kompetensi_dasar_id', 'asc');
		}, 'anggota_rombel', 'anggota_rombel.siswa', 'anggota_rombel.v_nilai_akhir_'.$nama_kompetensi => function($query){
			$query->where('pembelajaran_id', $request['pembelajaran_id']);
		}, 'anggota_rombel.nilai_remedial' => function($query){
			$query->where('pembelajaran_id', $request['pembelajaran_id']);
			$query->where('kompetensi_id', $request['kompetensi_id']);
		}])->find($pembelajaran_id);
        /*$kelompok_produktif = array(4, 5, 9, 10, 13);
        $nilai_kd = ($this->kompetensi_id == 1) ? 'pembelajaran.anggota_rombel.nilai_kd_pengetahuan' : 'pembelajaran.anggota_rombel.nilai_kd_keterampilan';
        //$this->rencana_penilaian = Rencana_penilaian::with(['pembelajaran', 'pembelajaran.rombongan_belajar', $nilai_kd])->find($this->rencana_penilaian_id);
        $get_mapel_agama = filter_agama_siswa($this->pembelajaran_id, $this->rombongan_belajar_id);
        $this->rencana_penilaian = Rencana_penilaian::with(['pembelajaran', 'pembelajaran.rombongan_belajar', 'pembelajaran.anggota_rombel' => function($query) use ($get_mapel_agama){
            if($this->kompetensi_id == 1){
                $query->with(['nilai_kd_pengetahuan']);
            } else {
                $query->with(['nilai_kd_keterampilan']);
            }
            if($get_mapel_agama){
                $query->whereHas('peserta_didik', function($query) use ($get_mapel_agama){
                    $query->where('agama_id', $get_mapel_agama);
                });
            }
        }])->find($this->rencana_penilaian_id);
        $this->bobot = $this->rencana_penilaian->bobot;
        $this->nama_rencana = $this->rencana_penilaian->nama_penilaian;
        $this->show = TRUE;
        foreach($this->rencana_penilaian->pembelajaran->anggota_rombel as $anggota_rombel){
            $skor_akhir = 0;
            if($this->kompetensi_id == 1){
                foreach($anggota_rombel->nilai_kd_pengetahuan as $kd_pengetahuan){
                    $skor_akhir += $kd_pengetahuan->nilai_kd;
                }
                $result = ($skor_akhir) ? number_format($skor_akhir / count($anggota_rombel->nilai_kd_pengetahuan), 0) : 0;
                $nilai_value[strtoupper($anggota_rombel->peserta_didik->nama)] = $result;
            } else {
                foreach($anggota_rombel->nilai_kd_keterampilan as $kd_keterampilan){
                    $skor_akhir += $kd_keterampilan->nilai_kd;
                }
                $result = ($skor_akhir) ? number_format($skor_akhir / count($anggota_rombel->nilai_kd_keterampilan), 0) : 0;
                $nilai_value[strtoupper($anggota_rombel->peserta_didik->nama)] = $result;
            }
        }
        $this->nilai_value = $nilai_value;
        $chart = [];
        $this->dispatchBrowserEvent('chart', ['chart' => [
            count(sebaran($nilai_value,100,90)),
            count(sebaran($nilai_value,89,86)),
            count(sebaran($nilai_value,85,80)),
            count(sebaran($nilai_value,79,75)),
            count(sebaran($nilai_value,74,70)),
            count(sebaran($nilai_value,69,65)),
            count(sebaran($nilai_value,64,60)),
            count(sebaran($nilai_value,59,54)),
            count(sebaran($nilai_value,54,50)),
            count(sebaran($nilai_value,49,0)),
        ]]);*/
    }
}
