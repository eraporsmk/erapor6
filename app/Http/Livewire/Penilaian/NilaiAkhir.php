<?php

namespace App\Http\Livewire\Penilaian;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rap2hpoutre\FastExcel\FastExcel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Rencana_penilaian;
use App\Models\Anggota_rombel;
use App\Models\Peserta_didik;
use App\Models\Nilai;
use App\Models\Nilai_remedial;
use App\Models\Nilai_akhir;
use App\Models\Deskripsi_mata_pelajaran;
use App\Models\Tujuan_pembelajaran;
use App\Models\Kompetensi_dasar;
use App\Models\Tp_nilai;
use App\Models\Kd_nilai;
use App\Imports\NilaiAkhirImport;
use Storage;

class NilaiAkhir extends Component
{
    use LivewireAlert, WithFileUploads;
    public $show = FALSE;
    public $semester_id;
    public $tingkat;
    public $jenis_rombel;
    public $rombongan_belajar_id;
    public $mata_pelajaran_id;
    public $pembelajaran_id;
    public $merdeka;
    public $data_siswa = [];
    public $data_tp = [];
    public $tp_dicapai = [];
    public $tp_belum_dicapai = [];
    public $data_kd = [];
    public $kd_dicapai = [];
    public $kd_belum_dicapai = [];
    public $nilai = [];
    public $template_excel;
    /*public $kompetensi_id;
    public $data_rombongan_belajar;
    public $data_pembelajaran;
    public $kd_nilai = [];
    
    public $rerata = [];
    public $remedial = [];
    public $deskripsi_dicapai = [];
    public $deskripsi_belum_dicapai = [];
    //inject
    public $kompetensi_dasar_id;
    public $class_input = [];
    */

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
        $this->reset(['jenis_rombel', 'show', 'merdeka', 'rombongan_belajar_id', 'pembelajaran_id', 'mata_pelajaran_id', 'data_siswa', 'data_tp', 'data_kd', 'tp_dicapai', 'tp_belum_dicapai', 'kd_dicapai', 'kd_belum_dicapai', 'nilai']);
        $this->dispatchBrowserEvent('tingkat', ['tingkat' => 'tingkat']);
    }
    public function updatedJenisRombel(){
        $this->reset(['show', 'merdeka', 'rombongan_belajar_id', 'pembelajaran_id', 'mata_pelajaran_id', 'data_siswa', 'data_tp', 'data_kd', 'tp_dicapai', 'tp_belum_dicapai', 'kd_dicapai', 'kd_belum_dicapai', 'nilai']);
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
    public function updatedRombonganBelajarId(){
        $this->reset(['show', 'merdeka', 'mata_pelajaran_id', 'pembelajaran_id', 'data_siswa', 'data_tp', 'data_kd', 'tp_dicapai', 'tp_belum_dicapai', 'kd_dicapai', 'kd_belum_dicapai', 'nilai']);
        if($this->rombongan_belajar_id){
            $rombel = Rombongan_belajar::find($this->rombongan_belajar_id);
            $this->merdeka = Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka');
            $data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $data_pembelajaran]);
        }
    }
    public function updatedMataPelajaranId(){
        $this->reset(['show', 'pembelajaran_id', 'data_siswa', 'data_tp', 'data_kd', 'tp_dicapai', 'tp_belum_dicapai', 'kd_dicapai', 'kd_belum_dicapai', 'nilai']);
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
            foreach($this->data_siswa as $siswa){
                foreach($siswa->anggota_rombel->tp_kompeten as $tp_kompeten){
                    $this->tp_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp_kompeten->tp_id] = $tp_kompeten->tp_id;
                }
                foreach($siswa->anggota_rombel->tp_inkompeten as $tp_inkompeten){
                    $this->tp_belum_dicapai[$siswa->anggota_rombel->anggota_rombel_id][$tp_inkompeten->tp_id] = $tp_inkompeten->tp_id;
                }
                $this->nilai[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->nilai_akhir_mapel) ? $siswa->anggota_rombel->nilai_akhir_mapel->nilai : '';
            }
            $this->dispatchBrowserEvent('data_siswa', ['data_siswa' => 'data_siswa']);
            //$data_rencana = Rencana_penilaian::select('rencana_penilaian_id', 'pembelajaran_id', 'kompetensi_id', 'nama_penilaian')->where('pembelajaran_id', $pembelajaran->pembelajaran_id)->where('kompetensi_id', $this->kompetensi_id)->get();
            //$this->dispatchBrowserEvent('data_rencana', ['data_rencana' => $data_rencana]);
        }
    }
    private function wherehas($query){
        if($this->merdeka){
            $query->whereHas('tp', function($query){
                $query->whereHas('cp', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                        $query->where($this->kondisi());
                    });
                });
            });
        } else {
            $query->whereHas('kd', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                    $query->where($this->kondisi());
                });
            });
        }
    }
    private function getSiswa(){
        $callback = function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            $query->with([
                'tp_kompeten' => function($query){
                    $this->wherehas($query);
                },
                'tp_inkompeten' => function($query){
                    $this->wherehas($query);
                },
                'nilai_akhir_mapel' => function($query){
                    if($this->merdeka){
                        $query->where('kompetensi_id', 4);
                    } else {
                        $query->where('kompetensi_id', 1);
                    }
                    $query->where('pembelajaran_id', $this->pembelajaran_id);
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
        if($this->merdeka){
            $this->data_tp = Tujuan_pembelajaran::whereHas('cp', function($query){
                $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
            })->orderBy('created_at')->get();
        } else {
            $this->data_tp = Tujuan_pembelajaran::whereHas('kd', function($query){
                $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
            })->orderBy('created_at')->get();
        }
    }
    public function store(){
        $this->validate(
            [
                'nilai.*' => 'nullable|numeric|min:0|max:100'
            ],
            [
                'nilai.*.numeric' => 'Nilai harus berupa angka!',
                'nilai.*.min' => 'Nilai tidak boleh di bawah 0 (nol)!',
                'nilai.*.max' => 'Nilai tidak boleh di atas 100 (seratus)!',
            ]
        );
        foreach($this->nilai as $anggota_rombel_id => $nilai_akhir){
            if($nilai_akhir > -1){
                Nilai_akhir::updateOrCreate(
                    [
                        'sekolah_id' => session('sekolah_id'),
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'pembelajaran_id' => $this->pembelajaran_id,
                        'kompetensi_id' => ($this->merdeka) ? 4 : 1,
                    ],
                    [
                        'nilai' => ($nilai_akhir) ? number_format($nilai_akhir,0) : 0,
                    ]
                );
                $this->simpan_tp_nilai($anggota_rombel_id);
            }
        }
        $this->flash('success', 'Nilai Akhir berhasil disimpan', [], '/penilaian/nilai-akhir');
    }
    private function simpan_tp_nilai($anggota_rombel_id){
        $tp_id = [];
        if(isset($this->tp_dicapai[$anggota_rombel_id])){
            foreach(array_filter($this->tp_dicapai[$anggota_rombel_id]) as $tp_dicapai){
                $tp_id[] = $tp_dicapai;
                $tp = Tujuan_pembelajaran::find($tp_dicapai);
                if($tp){
                    if($this->merdeka){
                        $update = [
                            'cp_id' => $tp->cp_id,
                        ];
                    } else {
                        $update = [
                            'kd_id' => $tp->kd_id,
                        ];
                    }
                    Tp_nilai::updateOrCreate(
                        [
                            'sekolah_id' => session('sekolah_id'),
                            'anggota_rombel_id' => $anggota_rombel_id,
                            'tp_id' => $tp_dicapai,
                            'kompeten' => 1,
                        ],
                        $update
                    );
                }
            }
        }
        $this->hapus_tp_nilai($tp_id, $anggota_rombel_id, 1);
        $tp_id = [];
        if(isset($this->tp_belum_dicapai[$anggota_rombel_id])){
            foreach(array_filter($this->tp_belum_dicapai[$anggota_rombel_id]) as $tp_belum_dicapai){
                $tp_id[] = $tp_belum_dicapai;
                $tp = Tujuan_pembelajaran::find($tp_belum_dicapai);
                if($tp){
                    if($this->merdeka){
                        $update = [
                            'cp_id' => $tp->cp_id,
                        ];
                    } else {
                        $update = [
                            'kd_id' => $tp->kd_id,
                        ];
                    }
                    Tp_nilai::updateOrCreate(
                        [
                            'sekolah_id' => session('sekolah_id'),
                            'anggota_rombel_id' => $anggota_rombel_id,
                            'tp_id' => $tp_belum_dicapai,
                            'kompeten' => 0,
                        ],
                        $update
                    );
                }
            }
        }
        $this->hapus_tp_nilai($tp_id, $anggota_rombel_id, 0);
    }
    public function updatedTpDicapai(){
        $this->getSiswa();
    }
    public function updatedTpBelumDicapai(){
        $this->getSiswa();
    }
    private function hapus_tp_nilai($tp_id, $anggota_rombel_id, $kompeten){
        if($this->merdeka){
            Tp_nilai::where('anggota_rombel_id', $anggota_rombel_id)->where('kompeten', $kompeten)->whereNotIn('tp_id', $tp_id)->whereHas('tp', function($query){
                $query->whereHas('cp', function($query){
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                    });
                });
            })->delete();
        } else {
            Tp_nilai::where('anggota_rombel_id', $anggota_rombel_id)->where('kompeten', $kompeten)->whereNotIn('tp_id', $tp_id)->whereHas('kd', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('mata_pelajaran_id', $this->mata_pelajaran_id);
                });
            })->delete();
        }
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
        Excel::import(new NilaiAkhirImport($this->rombongan_belajar_id, $this->pembelajaran_id, $this->merdeka), storage_path('/app/public/'.$file_path));
        Storage::disk('public')->delete($file_path);
        $this->flash('success', 'Template Nilai Akhir berhasil di import', [], '/penilaian/nilai-akhir');
        /*$sheets = (new FastExcel)->importSheets(storage_path('/app/public/'.$file_path));
        $sheet_nilai = $sheets[0];
        dd($sheet_nilai);
        $collection = collect($imported_data);
        foreach($collection as $nilai){
            $this->nilai[$nilai['PD_ID']] = $nilai['Nilai Akhir'];
            $this->deskripsi_dicapai[$nilai['PD_ID']] = $nilai['Kompetensi yang sudah dicapai'];
            $this->deskripsi_belum_dicapai[$nilai['PD_ID']] = $nilai['Kompetensi yang perlu ditingkatkan'];
        }
        */
    }
}
