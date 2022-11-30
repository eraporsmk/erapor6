<?php

namespace App\Http\Livewire\Penilaian;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Peserta_didik;
use App\Models\Deskripsi_mata_pelajaran;

class CapaianKompetensi extends Component
{
    use LivewireAlert;
    public $show = FALSE;
    public $show_reset = FALSE;
    public $semester_id;
    public $tingkat;
    public $jenis_rombel;
    public $rombongan_belajar_id;
    public $data_rombongan_belajar = [];
    public $pembelajaran_id;
    public $data_pembelajaran = [];
    public $data_siswa = [];
    public $deskripsi_kompeten;
    public $deskripsi_inkompeten;
    public $merdeka;
    public $mata_pelajaran_id;

    public function getListeners()
        {
            return [
                'confirmed' => 'confirmedReset',
            ];
        }

    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Penilaian'], ['name' => "Capaian Kompetensi"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $this->semester_id = session('semester_id');
        return view('livewire.penilaian.capaian-kompetensi', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    private function kondisi(){
        return function($query){
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->where('guru_id', $this->loggedUser()->guru_id);
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
        };
    }
    public function updatedTingkat(){
        $this->reset(['jenis_rombel', 'show', 'show_reset', 'merdeka', 'rombongan_belajar_id', 'pembelajaran_id', 'mata_pelajaran_id', 'data_siswa', 'deskripsi_kompeten', 'deskripsi_inkompeten']);
        $this->dispatchBrowserEvent('tingkat', ['tingkat' => 'tingkat']);
    }
    public function updatedJenisRombel(){
        $this->reset(['show', 'show_reset', 'merdeka', 'rombongan_belajar_id', 'pembelajaran_id', 'mata_pelajaran_id', 'data_siswa', 'deskripsi_kompeten', 'deskripsi_inkompeten']);
        if($this->jenis_rombel){
            $data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('jenis_rombel', $this->jenis_rombel);
                $query->whereHas('pembelajaran', $this->kondisi());
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId($value){
        $this->reset(['show', 'show_reset', 'merdeka', 'mata_pelajaran_id', 'pembelajaran_id', 'data_siswa', 'deskripsi_kompeten', 'deskripsi_inkompeten']);
        if($this->rombongan_belajar_id){
            $rombel = Rombongan_belajar::find($this->rombongan_belajar_id);
            $this->merdeka = Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka');
            $data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $data_pembelajaran]);
        }
    }
    public function updatedMataPelajaranId($value){
        $this->reset(['show', 'show_reset', 'pembelajaran_id', 'data_siswa', 'deskripsi_kompeten', 'deskripsi_inkompeten']);
        if($this->mata_pelajaran_id){
            $pembelajaran = Pembelajaran::where(function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
            })->first();
            $this->pembelajaran_id = $pembelajaran->pembelajaran_id;
            $this->getSiswa();
            $this->show = TRUE;
            //$data_rencana = Rencana_penilaian::select('rencana_penilaian_id', 'pembelajaran_id', 'kompetensi_id', 'nama_penilaian')->where('pembelajaran_id', $pembelajaran->pembelajaran_id)->where('kompetensi_id', $this->kompetensi_id)->get();
            //$this->dispatchBrowserEvent('data_rencana', ['data_rencana' => $data_rencana]);
        }
    }
    private function getSiswa(){
        $callback = function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            $query->with([
                'nilai_akhir_mapel' => function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                    if($this->merdeka){
                        $query->where('kompetensi_id', 4);
                    } else {
                        $query->where('kompetensi_id', 1);
                    }
                },
                'single_deskripsi_mata_pelajaran' => function($query){
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                },
                'tp_kompeten' => function($query){
                    if($this->merdeka){
                        $query->whereHas('tp', function($query){
                            $query->whereHas('cp', function($query){
                                $query->whereHas('pembelajaran', function($query){
                                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                                    $query->where($this->kondisi());
                                });
                            });
                        });
                        $query->with('tp');
                    } else {
                        $query->whereHas('kd', function($query){
                            $query->whereHas('pembelajaran', function($query){
                                $query->where('pembelajaran_id', $this->pembelajaran_id);
                                $query->where($this->kondisi());
                            });
                        });
                        $query->with('kd');
                    }
                },
                'tp_inkompeten' => function($query){
                    if($this->merdeka){
                        $query->whereHas('tp', function($query){
                            $query->whereHas('cp', function($query){
                                $query->whereHas('pembelajaran', function($query){
                                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                                    $query->where($this->kondisi());
                                });
                            });
                        });
                        $query->with('tp');
                    } else {
                        $query->whereHas('kd', function($query){
                            $query->whereHas('pembelajaran', function($query){
                                $query->where('pembelajaran_id', $this->pembelajaran_id);
                                $query->where($this->kondisi());
                            });
                        });
                        $query->with('tp');
                    }
                }
            ]);
        };
        $get_mapel_agama = filter_agama_siswa($this->pembelajaran_id, $this->rombongan_belajar_id);
        $this->data_siswa = Peserta_didik::where(function($query) use ($get_mapel_agama, $callback){
            $query->whereHas('anggota_rombel', $callback);
            if($get_mapel_agama){
                $query->where('agama_id', $get_mapel_agama);
            }
        })->with(['anggota_rombel' => $callback])->orderBy('nama')->get();
        foreach($this->data_siswa as $siswa){
            if($siswa->anggota_rombel->single_deskripsi_mata_pelajaran){
                $this->deskripsi_kompeten[$siswa->anggota_rombel->anggota_rombel_id] = $siswa->anggota_rombel->single_deskripsi_mata_pelajaran->deskripsi_pengetahuan;
                $this->deskripsi_inkompeten[$siswa->anggota_rombel->anggota_rombel_id] = $siswa->anggota_rombel->single_deskripsi_mata_pelajaran->deskripsi_keterampilan;
            } else {
                $this->deskripsi_kompeten[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->tp_kompeten->count()) ? 'Menunjukkan penguasaan yang baik dalam '.strtolower($siswa->anggota_rombel->tp_kompeten->implode('tp.deskripsi', ' dan ')) : NULL;
                $this->deskripsi_inkompeten[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->tp_inkompeten->count()) ? 'Perlu ditingkatkan dalam '.strtolower($siswa->anggota_rombel->tp_inkompeten->implode('tp.deskripsi', ' dan ')) : NULL;
            }
        }
        $this->show = TRUE;
        if(Deskripsi_mata_pelajaran::where('pembelajaran_id', $this->pembelajaran_id)->count()){
            $this->show_reset = TRUE;
        }
    }
    private function kondisi_old(){
        return function($query){
            $query->where('guru_id', $this->loggedUser()->guru_id);
            /*$query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            });*/
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            /*$query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            });*/
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
        };
    }
    public function changeTingkat(){
        $this->reset(['data_rombongan_belajar', 'rombongan_belajar_id', 'data_pembelajaran', 'pembelajaran_id', 'data_siswa', 'show', 'show_reset']);
        if($this->tingkat){
            $this->data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                //$query->where('jenis_rombel', 1);
                $query->whereHas('pembelajaran', $this->kondisi());
            })->get();
        }
    }
    public function changeRombel(){
        $this->reset(['data_pembelajaran', 'pembelajaran_id', 'data_siswa', 'show', 'show_reset']);
        if($this->rombongan_belajar_id){
            $this->data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
        }
    }
    public function changePembelajaran(){
        $this->reset(['data_siswa', 'show', 'show_reset']);
        if($this->pembelajaran_id){
            $pembelajaran = Pembelajaran::find($this->pembelajaran_id);
            $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            })->with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with([
                    'nilai_akhir_mapel' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                        $query->where('kompetensi_id', 4);
                    },
                    'single_deskripsi_mata_pelajaran' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    'tp_kompeten' => function($query){
                        $query->whereHas('tp', function($query){
                            $query->whereHas('cp', function($query){
                                $query->whereHas('pembelajaran', function($query){
                                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                                    $query->where($this->kondisi());
                                });
                            });
                        });
                        $query->with('tp');
                    },
                    'tp_inkompeten' => function($query){
                        $query->whereHas('tp', function($query){
                            $query->whereHas('cp', function($query){
                                $query->whereHas('pembelajaran', function($query){
                                    $query->where('pembelajaran_id', $this->pembelajaran_id);
                                    $query->where($this->kondisi());
                                });
                            });
                        });
                        $query->with('tp');
                    }
                ]);
                /*$query->with([
                    'nilai_rapor_pk' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    'nilai_kd_pk' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    'nilai_kd_pk_tertinggi' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    'nilai_kd_pk_terendah' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    
                ]);*/
            }])->orderBy('nama')->get();
            foreach($this->data_siswa as $siswa){
                if($siswa->anggota_rombel->single_deskripsi_mata_pelajaran){
                    $this->deskripsi_kompeten[$siswa->anggota_rombel->anggota_rombel_id] = $siswa->anggota_rombel->single_deskripsi_mata_pelajaran->deskripsi_pengetahuan;
                    $this->deskripsi_inkompeten[$siswa->anggota_rombel->anggota_rombel_id] = $siswa->anggota_rombel->single_deskripsi_mata_pelajaran->deskripsi_keterampilan;
                } else {
                    $this->deskripsi_kompeten[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->tp_kompeten->count()) ? 'Menunjukkan penguasaan yang baik dalam '.strtolower($siswa->anggota_rombel->tp_kompeten->implode('tp.deskripsi', ' dan ')) : NULL;
                    $this->deskripsi_inkompeten[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->tp_inkompeten->count()) ? 'Perlu ditingkatkan dalam '.strtolower($siswa->anggota_rombel->tp_inkompeten->implode('tp.deskripsi', ' dan ')) : NULL;
                }
            }
            $this->show = TRUE;
            if(Deskripsi_mata_pelajaran::where('pembelajaran_id', $this->pembelajaran_id)->count()){
                $this->show_reset = TRUE;
            }
        }
    }
    public function store(){
        foreach($this->deskripsi_kompeten as $anggota_rombel_id => $deskripsi_kompeten){
            Deskripsi_mata_pelajaran::updateOrCreate(
                [
                    'sekolah_id' => session('sekolah_id'),
                    'anggota_rombel_id' => $anggota_rombel_id,
                    'pembelajaran_id' => $this->pembelajaran_id,
                ],
                [
                    'deskripsi_pengetahuan' => $deskripsi_kompeten,
                    'deskripsi_keterampilan' => $this->deskripsi_inkompeten[$anggota_rombel_id],
                    'last_sync' => now(),
                ]
            );
        }
        $this->flash('success', 'Capaian Kompetensi berhasil disimpan', [], '/penilaian/capaian-kompetensi');
    }
    public function resetData(){
        $this->alert('question', 'Apakah Anda yakin?', [
            'text' => 'Tindakan ini tidak dapat dikembalikan!',
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yakin!',
            'showLoaderOnConfirm' => true,
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Batal',
            'onDismissed' => 'cancelled',
            'allowOutsideClick' => false,//'() => !Swal.isLoading()',
            'timer' => null
        ]);
    }
    public function confirmedReset(){
        if(Deskripsi_mata_pelajaran::where('pembelajaran_id', $this->pembelajaran_id)->delete()){
            $this->flash('success', 'Capaian Kompetensi berhasil direset', [], '/penilaian/capaian-kompetensi');
        } else {
            $this->flash('error', 'Capaian Kompetensi gagal direset', [], '/penilaian/capaian-kompetensi');
        }
    }
}
