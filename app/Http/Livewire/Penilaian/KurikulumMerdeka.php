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
use App\Models\Tp_nilai;
use App\Models\Nilai_tp;
use App\Models\Teknik_penilaian;
use App\Models\Nilai_sumatif;
use Storage;

class KurikulumMerdeka extends Component
{
    use LivewireAlert, WithFileUploads;
    public $kompetensi_id = 4;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $mata_pelajaran_id;
    public $jenis_sumatif;
    public $show = FALSE;
    public $show_rencana = FALSE;
    public $rencana_penilaian_id;
    public $data_siswa = [];
    public $kd_nilai = [];
    public $nilai = [];
    public $nilai_sumatif = [];
    public $rerata = [];
    public $pembelajaran_id;
    public $template_excel;

    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Penilaian'], ['name' => "Penilaian Kurikulum Merdeka"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $this->semester_id = session('semester_id');
        return view('livewire.penilaian.kurikulum-merdeka', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function updatedTingkat($value){
        $this->reset(['show', 'show_rencana', 'jenis_sumatif', 'data_siswa', 'kd_nilai', 'nilai', 'rerata']);
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
                    $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                });
            });
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                });
            });
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
        };
    }
    public function updatedRombonganBelajarId($value){
        $this->reset(['show', 'show_rencana', 'jenis_sumatif', 'data_siswa', 'kd_nilai', 'nilai', 'rerata']);
        if($this->rombongan_belajar_id){
            $data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $data_pembelajaran]);
        }
    }
    public function updatedMataPelajaranId($value){
        $this->reset(['show', 'show_rencana', 'data_siswa', 'kd_nilai', 'nilai', 'rerata', 'jenis_sumatif']);
        if($this->mata_pelajaran_id){
            $pembelajaran = Pembelajaran::where(function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
            })->first();
            $this->pembelajaran_id = $pembelajaran->pembelajaran_id;
            $data_bentuk_penilaian = Teknik_penilaian::where('kompetensi_id', $this->kompetensi_id)->get();
            $this->dispatchBrowserEvent('data_bentuk_penilaian', ['data_bentuk_penilaian' => $data_bentuk_penilaian]);
        }
    }
    public function updatedJenisSumatif(){
        $this->reset(['show', 'show_rencana', 'data_siswa', 'kd_nilai', 'nilai', 'rerata']);
        if($this->jenis_sumatif){
            if($this->jenis_sumatif == 'Sumatif Lingkup Materi'){
                $data_rencana = Rencana_penilaian::select('rencana_penilaian_id', 'pembelajaran_id', 'kompetensi_id', 'nama_penilaian')->where('pembelajaran_id', $this->pembelajaran_id)->where('kompetensi_id', $this->kompetensi_id)->get();
                $this->dispatchBrowserEvent('data_rencana', ['data_rencana' => $data_rencana]);
                $this->show_rencana = TRUE;
            } else {
                $this->show = TRUE;
                $get_mapel_agama = filter_agama_siswa($this->pembelajaran_id, $this->rombongan_belajar_id);
                $this->data_siswa = Peserta_didik::select('peserta_didik_id', 'nama', 'nisn')->where(function($query) use ($get_mapel_agama){
                    $query->whereHas('anggota_rombel', function($query){
                        $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                    });
                    if($get_mapel_agama){
                        $query->where('agama_id', $get_mapel_agama);
                    }
                })->with(['anggota_rombel' => function($query){
                    $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                    $query->with(['nilai_sumatif' => function($query){
                        $query->whereHas('pembelajaran', function($query){
                            $query->where('pembelajaran_id', $this->pembelajaran_id);
                        });
                    }]);
                }])->orderBy('nama')->get();
                foreach($this->data_siswa as $siswa){
                    $this->nilai_sumatif[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->nilai_sumatif) ? $siswa->anggota_rombel->nilai_sumatif->nilai : NULL;
                }
            }
        }
    }
    public function updatedRencanaPenilaianId($va){
        $this->reset(['data_siswa', 'kd_nilai', 'nilai', 'rerata']);
        if($this->rencana_penilaian_id){
            $get_mapel_agama = filter_agama_siswa($this->pembelajaran_id, $this->rombongan_belajar_id);
            $this->data_siswa = Peserta_didik::select('peserta_didik_id', 'nama', 'nisn')->where(function($query) use ($get_mapel_agama){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                });
                if($get_mapel_agama){
                    $query->where('agama_id', $get_mapel_agama);
                }
            })->with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with(['nilai_tp' => function($query){
                    $query->whereHas('tp_nilai', function($query){
                        $query->whereHas('rencana_penilaian', function($query){
                            $query->where('rencana_penilaian_id', $this->rencana_penilaian_id);
                        });
                    });
                }]);
            }])->orderBy('nama')->get();
            $this->show = TRUE;
            $this->kd_nilai = Tp_nilai::with(['tp'])->where('rencana_penilaian_id', $this->rencana_penilaian_id)->select('tp_nilai_id', 'rencana_penilaian_id', 'tp_id')->get();
            foreach($this->data_siswa as $siswa){
                $cek_nilai = FALSE;
                foreach($siswa->anggota_rombel->nilai_tp as $nilai_tp){
                    $cek_nilai = TRUE;
                    $this->nilai[$siswa->anggota_rombel->anggota_rombel_id][$nilai_tp->tp_nilai_id] = $nilai_tp->nilai;
                }
                if($cek_nilai){
                    $this->rerata[$siswa->anggota_rombel->anggota_rombel_id] = bilangan_bulat(collect($this->nilai[$siswa->anggota_rombel->anggota_rombel_id])->avg());
                }
            }
        }
        $this->dispatchBrowserEvent('tooltip');
    }
    public function hitungRerata($anggota_rombel_id)
    {
        $this->rerata[$anggota_rombel_id] = bilangan_bulat(collect($this->nilai[$anggota_rombel_id])->avg());
    }
    public function store(){
        if($this->nilai_sumatif){
            foreach($this->nilai_sumatif as $anggota_rombel_id => $nilai){
                if($nilai){
                    Nilai_sumatif::updateOrCreate(
                        [
                            'sekolah_id' => session('sekolah_id'),
                            'pembelajaran_id' => $this->pembelajaran_id,
                            'anggota_rombel_id' => $anggota_rombel_id,
                        ],
                        [
                            'nilai' => $nilai,
                        ]
                    );
                }
            }
        } else {
            foreach($this->nilai as $anggota_rombel_id => $tp_nilai){
                foreach($tp_nilai as $tp_nilai_id => $nilai){
                    if($nilai){
                        Nilai_tp::updateOrCreate(
                            [
                                'sekolah_id' => session('sekolah_id'),
                                'tp_nilai_id' => $tp_nilai_id,
                                'anggota_rombel_id' => $anggota_rombel_id,
                            ],
                            [
                                'nilai' => $nilai,
                                //'rerata' => number_format(array_sum($tp_nilai)/count($tp_nilai), 0),
                            ]
                        );
                    }
                }
            }    
        }
        $this->flash('success', 'Nilai berhasil disimpan', [], '/penilaian/kurikulum-merdeka');
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
        //dd($collection);
        $error = 0;
        foreach($collection as $nilai){
            //$this->rencana_penilaian_id
            $anggota_rombel_id = $nilai['PD_ID'];
            unset($nilai['No'], $nilai['PD_ID'], $nilai['Nama Peserta Didik'], $nilai['NISN']);
            foreach($nilai as $deskripsi => $nilai_tp){
                $deskripsi = str_replace("'", '', $deskripsi);
                $tp_nilai = Tp_nilai::where('rencana_penilaian_id', $this->rencana_penilaian_id)->whereHas('tp', function($query) use ($deskripsi){
                    $query->where('deskripsi', $deskripsi);
                })->first();
                if($tp_nilai){
                    $this->nilai[$anggota_rombel_id][$tp_nilai->tp_nilai_id] = $nilai_tp;
                }
            }
            if(isset($this->nilai[$anggota_rombel_id])){
                $filtered = collect($this->nilai[$anggota_rombel_id])->filter(function ($value, $key) {
                    return $value > 0;
                });
                $nilai = $filtered->all();
                $this->rerata[$anggota_rombel_id] = ($nilai) ? bilangan_bulat($filtered->avg()) : NULL;
            } else {
                $error++;
            }
        }
        if($error){
            $this->alert('error', 'Template Excel salah!', [
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'confirmed',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        }
        Storage::disk('public')->delete($file_path);
    }
}
