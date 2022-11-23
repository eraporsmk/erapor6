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
use Storage;

class Pengetahuan extends Component
{
    use LivewireAlert, WithFileUploads;
    public $kompetensi_id = 1;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $mata_pelajaran_id;
    public $pembelajaran_id;
    public $show = FALSE;
    public $rencana_penilaian_id;
    public $data_siswa = [];
    public $kd_nilai = [];
    public $nilai = [];
    public $rerata = [];
    public $template_excel;

    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Penilaian'], ['name' => "Pengetahuan"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $this->semester_id = session('semester_id');
        return view('livewire.penilaian.pengetahuan', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function updatedTingkat($value){
        $this->reset(['show', 'data_siswa', 'kd_nilai', 'nilai', 'rerata']);
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
        $this->reset(['show', 'data_siswa', 'kd_nilai', 'nilai', 'rerata']);
        if($this->rombongan_belajar_id){
            $data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $data_pembelajaran]);
        }
    }
    public function updatedMataPelajaranId($value){
        $this->reset(['show', 'data_siswa', 'kd_nilai', 'nilai', 'rerata']);
        if($this->mata_pelajaran_id){
            $pembelajaran = Pembelajaran::where(function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
            })->first();
            $this->pembelajaran_id = $pembelajaran->pembelajaran_id;
            $data_rencana = Rencana_penilaian::select('rencana_penilaian_id', 'pembelajaran_id', 'kompetensi_id', 'nama_penilaian')->where('pembelajaran_id', $pembelajaran->pembelajaran_id)->where('kompetensi_id', $this->kompetensi_id)->get();
            $this->dispatchBrowserEvent('data_rencana', ['data_rencana' => $data_rencana]);
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
                $query->with(['nilai_kd' => function($query){
                    $query->whereHas('kd_nilai', function($query){
                        $query->whereHas('rencana_penilaian', function($query){
                            $query->where('rencana_penilaian_id', $this->rencana_penilaian_id);
                        });
                    });
                }]);
            }])->orderBy('nama')->get();
            $this->show = TRUE;
            $this->kd_nilai = Kd_nilai::where('rencana_penilaian_id', $this->rencana_penilaian_id)->select('kd_nilai_id', 'rencana_penilaian_id', 'id_kompetensi')->get();
            foreach($this->data_siswa as $siswa){
                $cek_nilai = FALSE;
                foreach($siswa->anggota_rombel->nilai_kd as $nilai_kd){
                    $cek_nilai = TRUE;
                    $this->nilai[$siswa->anggota_rombel->anggota_rombel_id][$nilai_kd->kd_nilai_id] = $nilai_kd->nilai;
                }
                if($cek_nilai){
                    $this->rerata[$siswa->anggota_rombel->anggota_rombel_id] = bilangan_bulat(collect($this->nilai[$siswa->anggota_rombel->anggota_rombel_id])->avg());
                }
            }
        }
    }
    public function hitungRerata($anggota_rombel_id)
    {
        $this->rerata[$anggota_rombel_id] = bilangan_bulat(collect($this->nilai[$anggota_rombel_id])->avg());
    }
    public function store(){
        foreach($this->nilai as $anggota_rombel_id => $kd_nilai){
            foreach($kd_nilai as $kd_nilai_id => $nilai){
                if($nilai){
                    Nilai::updateOrCreate(
                        [
                            'sekolah_id' => session('sekolah_id'),
                            'kd_nilai_id' => $kd_nilai_id,
                            'anggota_rombel_id' => $anggota_rombel_id,
                            'kompetensi_id' => $this->kompetensi_id,
                        ],
                        [
                            'nilai' => $nilai,
                            'rerata' => number_format(array_sum($kd_nilai)/count($kd_nilai), 0),
                        ]
                    );
                }
            }
        }
        $this->flash('success', 'Nilai berhasil disimpan', [], '/penilaian/pengetahuan');
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
        $sheets = (new FastExcel)->importSheets(storage_path('/app/public/'.$file_path));
        $collection = collect($sheets[0]);
        $i=0;
        foreach($collection as $key => $nilai){
            $anggota_rombel_id = $nilai['PD_ID'];
            unset($nilai['No'], $nilai['PD_ID'], $nilai['Nama Peserta Didik'], $nilai['NISN']);
            foreach($nilai as $id_kompetensi => $nilai_kd){
                if(isset($sheets[1][$key]['ID_KD'])){
                    $id_kompetensi = str_replace("'", '', $id_kompetensi);
                    $kd_nilai = Kd_nilai::find($sheets[1][$key]['PD_ID']);
                    if($kd_nilai && $kd_nilai->id_kompetensi == $id_kompetensi && $sheets[1][$key]['PD_ID'] == $anggota_rombel_id){
                        $i++;
                        $this->nilai[$anggota_rombel_id][$sheets[1][$key]['ID_KD']] = $nilai_kd;
                    }
                }
            }
            if(isset($this->nilai[$anggota_rombel_id])){
                $filtered = collect($this->nilai[$anggota_rombel_id])->filter(function ($value, $key) {
                    return $value > 0;
                });
                $nilai = $filtered->all();
                $this->rerata[$anggota_rombel_id] = ($nilai) ? bilangan_bulat($filtered->avg()) : NULL;
            }
        }
        if(!$i){
            $this->alert('error', 'Import Nilai Gagal', [
                'text' => 'Format template tidak sesuai!',
                'toast' => false,
                'timer' => null
            ]);
        }
        /*dd($collection);
        $imported_data = (new FastExcel)->import(storage_path('/app/public/'.$file_path));
        $collection = collect($imported_data);
        //dd($collection);
        foreach($collection as $nilai){
            //$this->rencana_penilaian_id
            $anggota_rombel_id = $nilai['PD_ID'];
            unset($nilai['No'], $nilai['PD_ID'], $nilai['Nama Peserta Didik'], $nilai['NISN']);
            foreach($nilai as $id_kompetensi => $nilai_kd){
                $id_kompetensi = str_replace("'", '', $id_kompetensi);
                $kd_nilai = Kd_nilai::where('rencana_penilaian_id', $this->rencana_penilaian_id)->where('id_kompetensi', $id_kompetensi)->first();
                if($kd_nilai){
                    $this->nilai[$anggota_rombel_id][$kd_nilai->kd_nilai_id] = $nilai_kd;
                }
            }
            if(isset($this->nilai[$anggota_rombel_id])){
                $filtered = collect($this->nilai[$anggota_rombel_id])->filter(function ($value, $key) {
                    return $value > 0;
                });
                $nilai = $filtered->all();
                $this->rerata[$anggota_rombel_id] = ($nilai) ? bilangan_bulat($filtered->avg()) : NULL;
            }
        }*/
        Storage::disk('public')->delete($file_path);
    }
}
