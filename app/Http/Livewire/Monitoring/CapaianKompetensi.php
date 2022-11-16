<?php

namespace App\Http\Livewire\Monitoring;

use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Peserta_didik;
use App\Models\Kd_nilai;

class CapaianKompetensi extends Component
{
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $pembelajaran_id;
    public $kompetensi_id;
    public $kompetensi_dasar_id;
    public $data_kd = [];
    public $data_siswa = [];
    public $show = FALSE;
    public $nama_rombel;
    public $nama_mapel;
    public $kkm;
    public $kompetensi_dasar;
    public $capaian_kompetensi = [];

    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.monitoring.capaian-kompetensi', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Monitoring'], ['name' => "Pencapaian Kompetensi"]
            ]
        ]);
    }
    private function loggedUser(){
        return auth()->user();
    }
    public function updatedTingkat($value){
        $this->reset(['show', 'data_siswa', 'rombongan_belajar_id']);
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
        $this->reset(['show', 'data_siswa', 'pembelajaran_id']);
        if($this->rombongan_belajar_id){
            $data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $data_pembelajaran]);
        }
    }
    public function updatedPembelajaranId($value){
        $this->reset(['show', 'data_siswa', 'kompetensi_id']);
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
        $this->reset(['show', 'data_siswa', 'kompetensi_dasar_id', 'data_kd']);
        if($this->kompetensi_id){
            $callback = function($query){
                $query->where('pembelajaran_id', $this->pembelajaran_id);
                $query->where('kompetensi_id', $this->kompetensi_id);
            };          
            $this->data_kd = Kd_nilai::whereHas('rencana_penilaian', $callback)->with(['rencana_penilaian' => $callback])->orderBy('id_kompetensi', 'asc')->get();
            $this->dispatchBrowserEvent('data_kd', ['data_kd' => $this->data_kd]);
            /*$kelompok_produktif = array(4, 5, 9, 10, 13);
            $mapel_produktif = NULL;
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
            }*/
        }
    }
    public function updatedKompetensiDasarId(){
        $this->reset(['show', 'data_siswa']);
        $nama_kompetensi = ($this->kompetensi_id == 1) ? 'pengetahuan' : 'keterampilan';
        $get_mapel_agama = filter_agama_siswa($this->pembelajaran_id, $this->rombongan_belajar_id);
        /*
        $pembelajaran = Pembelajaran::with('rombongan_belajar')->with([
            'kd_nilai_capaian' => function($q) use ($nama_kompetensi){
                $q->with('kompetensi_dasar');
                $q->where('rencana_penilaian.kompetensi_id', $this->kompetensi_id);
                $q->where('kompetensi_dasar_id', $this->kompetensi_dasar_id);
            }, 
            'anggota_rombel' => function($query) use ($nama_kompetensi, $get_mapel_agama){
                if($get_mapel_agama){
                    $query->whereHas('peserta_didik', function($query) use ($get_mapel_agama){
                        $query->where('agama_id', $get_mapel_agama);
                    });
                }
			    $query->where('jenis_rombel', 1);
                $query->with(['peserta_didik' => function($query) use ($get_mapel_agama){
                    if($get_mapel_agama){
                        $query->where('agama_id', $get_mapel_agama);
                    }
                }]);
                $query->with(['nilai_kd_'.$nama_kompetensi => function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                }]);
		    },
        ])->find($this->pembelajaran_id);
        */
        $pembelajaran = Pembelajaran::find($this->pembelajaran_id);
        $kkm = get_kkm($pembelajaran->kelompok_id, 0);
        $this->kompetensi_dasar = $pembelajaran->kd_nilai_capaian->kompetensi_dasar->kompetensi_dasar;
        $this->show = TRUE;
        $data_siswa = Peserta_didik::where(function($query) use ($get_mapel_agama){
            if($get_mapel_agama){
                $query->where('agama_id', $get_mapel_agama);
            }
            $query->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            });
        })->select('peserta_didik_id', 'nama')->orderBy('nama')->with([
            'anggota_rombel' => function($query) use ($nama_kompetensi){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with(['nilai_kd_'.$nama_kompetensi => function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                }]);
            }
        ])->get();
        $nilai_kd = [];
        $skm = [];
        $with = 'nilai_kd_'.$nama_kompetensi;
        foreach($data_siswa as $siswa){
            $nilai_kd[] = number_format($siswa->anggota_rombel->{$with}->avg('nilai_kd'), 0);
            $skm[] = $kkm;
        }
        $this->dispatchBrowserEvent('chart', ['chart' => [
            'data_siswa' => $data_siswa->pluck('nama'),
            'nilai_kd' => $nilai_kd,
            'skm' => $skm,
        ]]);
    }
}
