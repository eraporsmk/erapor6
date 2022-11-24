<?php

namespace App\Http\Livewire\Penilaian;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rap2hpoutre\FastExcel\FastExcel;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Rencana_penilaian;
use App\Models\Anggota_rombel;
use App\Models\Peserta_didik;
use App\Models\Kd_nilai;
use App\Models\Nilai;
use App\Models\Nilai_remedial;
use App\Models\Nilai_akhir;
use App\Models\Deskripsi_mata_pelajaran;
use App\Models\Tujuan_pembelajaran;
use App\Models\Tp_nilai;
use Storage;

class NilaiAkhir extends Component
{
    use LivewireAlert, WithFileUploads;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $mata_pelajaran_id;
    public $kompetensi_id;
    public $data_rombongan_belajar;
    public $data_pembelajaran;
    public $pembelajaran_id;
    public $show = FALSE;
    public $data_siswa = [];
    public $kd_nilai = [];
    public $nilai = [];
    public $rerata = [];
    public $remedial = [];
    public $deskripsi_dicapai = [];
    public $deskripsi_belum_dicapai = [];
    //inject
    public $kompetensi_dasar_id;
    public $class_input = [];
    public $template_excel;
    public $data_tp = [];
    public $tp_dicapai;
    public $tp_belum_dicapai;

    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Penilaian'], ['name' => "Input Nilai Akhir"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $this->semester_id = session('semester_id');
        return view('livewire.penilaian.nilai-akhir', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function changeTingkat(){
        $this->pembelajaran = NULL;
        $this->rombongan_belajar_id = NULL;
        if($this->tingkat){
            $this->data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                //$query->whereIn('jenis_rombel', [1, 16]);
                $query->whereHas('pembelajaran', $this->kondisi());
            })->get();
            $this->data_pembelajaran = NULL;
            $this->data_rencana = NULL;
        } else {
            $this->data_rombongan_belajar = NULL;
            $this->data_pembelajaran = NULL;
            $this->data_rencana = NULL;
        }
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
    public function changeRombel(){
        if($this->rombongan_belajar_id){
            $this->data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
        } else {
            $this->data_pembelajaran = NULL;
        }
        $this->mata_pelajaran_id = NULL;
    }
    public function changePembelajaran(){
        $this->reset(['pembelajaran_id', 'data_siswa', 'show', 'nilai', 'tp_dicapai', 'tp_belum_dicapai']);
        if($this->mata_pelajaran_id){
            $callback = function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with([
                    'tp_kompeten' => function($query){
                        $query->whereHas('tp', function($query){
                            $query->whereHas('cp', function($query){
                                $query->whereHas('pembelajaran', function($query){
                                    $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                                    $query->where($this->kondisi());
                                });
                            });
                        });
                        //$query->with('tp');
                    },
                    'tp_inkompeten' => function($query){
                        $query->whereHas('tp', function($query){
                            $query->whereHas('cp', function($query){
                                $query->whereHas('pembelajaran', function($query){
                                    $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                                    $query->where($this->kondisi());
                                });
                            });
                        });
                        //$query->with('tp');
                    },
                    'nilai_akhir_mapel' => function($query){
                        $query->where('kompetensi_id', 4);
                        //$query->where('pembelajaran_id', $this->pembelajaran_id);
                        $query->whereHas('pembelajaran', function($query){
                            $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                            $query->where($this->kondisi());
                        });
                    }
                    /*,
                    'single_deskripsi_mata_pelajaran' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    }
                    'nilai_remedial' => function($query){
                        $query->where('kompetensi_id', $this->kompetensi_id);
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    $with_1 => function($query){
                        $query->with(['kd_nilai' => function($query){
                            $query->wherehas('rencana_penilaian', function($query){
                                $query->where('kompetensi_id', $this->kompetensi_id);
                                $query->where('pembelajaran_id', $this->pembelajaran_id);
                            });
                        }]);
                        $query->where('kompetensi_id', $this->kompetensi_id);
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                        $query->orderBy('kompetensi_dasar_id');
                    },
                    $with_2 => function($query){
                        $query->where('kompetensi_id', $this->kompetensi_id);
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    }*/
                ]);
            };
            $this->data_tp = Tujuan_pembelajaran::whereHas('cp', function($query){
                $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
            })->orderBy('created_at')->get();
            $pembelajaran = Pembelajaran::where('rombongan_belajar_id', $this->rombongan_belajar_id)->where('mata_pelajaran_id', $this->mata_pelajaran_id)->first();
            $this->pembelajaran_id = $pembelajaran->pembelajaran_id;
            $get_mapel_agama = filter_agama_siswa($this->pembelajaran_id, $this->rombongan_belajar_id);
            $this->data_siswa = Peserta_didik::where(function($query) use ($get_mapel_agama, $callback){
                $query->whereHas('anggota_rombel', $callback);
                if($get_mapel_agama){
                    $query->where('agama_id', $get_mapel_agama);
                }
            })->with(['anggota_rombel' => $callback])->orderBy('nama')->get();
            foreach($this->data_siswa as $data_siswa){
                $this->nilai[$data_siswa->anggota_rombel->anggota_rombel_id] = ($data_siswa->anggota_rombel->nilai_akhir_mapel) ? $data_siswa->anggota_rombel->nilai_akhir_mapel->nilai : NULL;
                foreach($data_siswa->anggota_rombel->tp_kompeten as $tp_kompeten){
                    $this->tp_dicapai[$data_siswa->anggota_rombel->anggota_rombel_id][$tp_kompeten->tp_id] = $tp_kompeten->tp_id;
                }
                foreach($data_siswa->anggota_rombel->tp_inkompeten as $tp_inkompeten){
                    $this->tp_belum_dicapai[$data_siswa->anggota_rombel->anggota_rombel_id][$tp_inkompeten->tp_id] = $tp_inkompeten->tp_id;
                }
                //wire:model="tp_dicapai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$tp->tp_id}}"
                //wire:model="tp_belum_dicapai.{{$siswa->anggota_rombel->anggota_rombel_id}}.{{$tp->tp_id}}"
                //$this->deskripsi_dicapai[$data_siswa->anggota_rombel->anggota_rombel_id] = ($data_siswa->anggota_rombel->single_deskripsi_mata_pelajaran) ? $data_siswa->anggota_rombel->single_deskripsi_mata_pelajaran->deskripsi_pengetahuan : NULL;
                //$this->deskripsi_belum_dicapai[$data_siswa->anggota_rombel->anggota_rombel_id] = ($data_siswa->anggota_rombel->single_deskripsi_mata_pelajaran) ? $data_siswa->anggota_rombel->single_deskripsi_mata_pelajaran->deskripsi_keterampilan : NULL;
            }
            $this->show = TRUE;
        }
    }
    public function store(){
        foreach($this->nilai as $anggota_rombel_id => $nilai_akhir){
            if($nilai_akhir > -1){
                Nilai_akhir::updateOrCreate(
                    [
                        'sekolah_id' => session('sekolah_id'),
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'pembelajaran_id' => $this->pembelajaran_id,
                        'kompetensi_id' => 4,
                    ],
                    [
                        'nilai' => $nilai_akhir,
                    ]
                );
                $tp_id = [];
                if(isset($this->tp_dicapai[$anggota_rombel_id])){
                    foreach(array_filter($this->tp_dicapai[$anggota_rombel_id]) as $tp_dicapai){
                        $tp = Tujuan_pembelajaran::find($tp_dicapai);
                        $tp_id[] = $tp_dicapai;
                        Tp_nilai::updateOrCreate(
                            [
                                'sekolah_id' => session('sekolah_id'),
                                'anggota_rombel_id' => $anggota_rombel_id,
                                'tp_id' => $tp_dicapai,
                                'kompeten' => 1,
                            ],
                            [
                                'cp_id' => $tp->cp_id,
                            ]
                        );
                    }
                }
                $this->hapus_tp_nilai($tp_id, $anggota_rombel_id, 1);
                $tp_id = [];
                if(isset($this->tp_belum_dicapai[$anggota_rombel_id])){
                    foreach(array_filter($this->tp_belum_dicapai[$anggota_rombel_id]) as $tp_belum_dicapai){
                        $tp = Tujuan_pembelajaran::find($tp_belum_dicapai);
                        $tp_id[] = $tp_belum_dicapai;
                        Tp_nilai::updateOrCreate(
                            [
                                'sekolah_id' => session('sekolah_id'),
                                'anggota_rombel_id' => $anggota_rombel_id,
                                'tp_id' => $tp_belum_dicapai,
                                'kompeten' => 0,
                            ],
                            [
                                'cp_id' => $tp->cp_id,
                            ]
                        );
                    }
                }
                $this->hapus_tp_nilai($tp_id, $anggota_rombel_id, 0);
            }
        }
        $this->flash('success', 'Nilai Akhir berhasil disimpan', [], '/penilaian/nilai-akhir');
    }
    private function hapus_tp_nilai($tp_id, $anggota_rombel_id, $kompeten){
        Tp_nilai::where('anggota_rombel_id', $anggota_rombel_id)->where('kompeten', $kompeten)->whereNotIn('tp_id', $tp_id)->whereHas('tp', function($query){
            $query->whereHas('cp', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where($this->kondisi());
                });
            });
        })->delete();
    }
    public function updatedTemplateExcel()
    {
        $this->validate(
            [
                'template_excel' => 'mimes:xlsx', // 1MB Max
            ],
            [
                'template_excel.mimes' => 'File harus berupa file dengan ekstensi: xlsx.',
            ]
        );
        $file_path = $this->template_excel->store('files', 'public');
        $imported_data = (new FastExcel)->import(storage_path('/app/public/'.$file_path));
        $collection = collect($imported_data);
        foreach($collection as $nilai){
            $this->nilai[$nilai['PD_ID']] = $nilai['Nilai Akhir'];
            $this->deskripsi_dicapai[$nilai['PD_ID']] = $nilai['Kompetensi yang sudah dicapai'];
            $this->deskripsi_belum_dicapai[$nilai['PD_ID']] = $nilai['Kompetensi yang perlu ditingkatkan'];
        }
        Storage::disk('public')->delete($file_path);
    }
}
