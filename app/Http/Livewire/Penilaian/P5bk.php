<?php

namespace App\Http\Livewire\Penilaian;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Anggota_rombel;
use App\Models\Peserta_didik;
use App\Models\Rencana_budaya_kerja;
use App\Models\Aspek_budaya_kerja;
use App\Models\Nilai_budaya_kerja;
use App\Models\Opsi_budaya_kerja;
use App\Models\Catatan_budaya_kerja;
use Helper;

class P5bk extends Component
{
    use LivewireAlert;
    public $show = FALSE;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $pembelajaran_id;
    public $rencana_penilaian_id;
    public $rencana_budaya_kerja = [];
    public $jumlah_elemen = 0;
    public $aspek_budaya_kerja  = [];
    public $data_siswa = [];
    public $opsi_budaya_kerja = [];
    public $nilai = [];
    public $deskripsi = [];

    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], 
            ['link' => '#', 'name' => 'Penilaian'], 
            ['name' => "Projek Penguatan Profil Pelajar Pancasila"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $this->semester_id = session('semester_id');
        return view('livewire.penilaian.p5bk', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function updatedTingkat($value){
        $this->reset(['show', 'data_siswa', 'aspek_budaya_kerja', 'nilai', 'deskripsi']);
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
            //$query->whereNotNull('induk_pembelajaran_id');
            $query->has('tema');
            $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            //$query->whereNotNull('induk_pembelajaran_id');
            $query->has('tema');
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
        };
    }
    public function updatedRombonganBelajarId($value){
        $this->reset(['show', 'data_siswa', 'opsi_budaya_kerja', 'rencana_budaya_kerja', 'nilai', 'deskripsi']);
        if($this->rombongan_belajar_id){
            $this->opsi_budaya_kerja = Opsi_budaya_kerja::where('opsi_id', '<>', 1)->orderBy('updated_at', 'ASC')->get();
            $this->rencana_budaya_kerja = Rencana_budaya_kerja::whereHas('pembelajaran', function($query){
                $query->where($this->kondisi());
            })->get();
            $this->jumlah_elemen = Aspek_budaya_kerja::whereHas('rencana_budaya_kerja', function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where($this->kondisi());
                });
            })->count();
            $this->data_siswa = Peserta_didik::select('peserta_didik_id', 'nama', 'nisn')->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            })->with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with(['nilai_budaya_kerja' => function($query){
                    $query->whereHas('aspek_budaya_kerja', function($query){
                        $query->whereHas('rencana_budaya_kerja', function($query){
                            $query->whereHas('pembelajaran', function($query){
                                $query->where($this->kondisi());
                            });
                        });
                    });
                }, 'catatan_budaya_kerja']);
            }])->orderBy('nama')->get();
            foreach($this->data_siswa as $siswa){
                foreach($siswa->anggota_rombel->nilai_budaya_kerja as $nilai_budaya_kerja){
                    $this->nilai[$siswa->anggota_rombel->anggota_rombel_id][$nilai_budaya_kerja->aspek_budaya_kerja_id] = $nilai_budaya_kerja->opsi_id.'|'.$nilai_budaya_kerja->elemen_id;
                    $this->deskripsi[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->catatan_budaya_kerja) ? $siswa->anggota_rombel->catatan_budaya_kerja->catatan : '';
                }
            }
            $this->show = TRUE;
            $this->dispatchBrowserEvent('tooltip');
            //$data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            //$this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $data_pembelajaran]);
        }
    }
    public function updatedPembelajaranId($value){
        $this->reset(['show', 'data_siswa', 'rencana_budaya_kerja', 'jumlah_elemen', 'nilai', 'deskripsi']);
        if($this->pembelajaran_id){
            $this->opsi_budaya_kerja = Opsi_budaya_kerja::where('opsi_id', '<>', 1)->orderBy('updated_at', 'ASC')->get();
            $this->rencana_budaya_kerja = Rencana_budaya_kerja::where('pembelajaran_id', $this->pembelajaran_id)->get();
            $this->jumlah_elemen = Aspek_budaya_kerja::whereHas('rencana_budaya_kerja', function($query){
                $query->where('pembelajaran_id', $this->pembelajaran_id);
            })->count();
            $this->data_siswa = Peserta_didik::select('peserta_didik_id', 'nama', 'nisn')->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            })->with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with(['nilai_budaya_kerja' => function($query){
                    $query->whereHas('aspek_budaya_kerja', function($query){
                        $query->whereHas('rencana_budaya_kerja', function($query){
                            $query->where('pembelajaran_id', $this->pembelajaran_id);
                        });
                    });
                }, 'catatan_budaya_kerja']);
            }])->orderBy('nama')->get();
            foreach($this->data_siswa as $siswa){
                foreach($siswa->anggota_rombel->nilai_budaya_kerja as $nilai_budaya_kerja){
                    $this->nilai[$siswa->anggota_rombel->anggota_rombel_id][$nilai_budaya_kerja->aspek_budaya_kerja_id] = $nilai_budaya_kerja->opsi_id.'|'.$nilai_budaya_kerja->elemen_id;
                    $this->deskripsi[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->catatan_budaya_kerja) ? $siswa->anggota_rombel->catatan_budaya_kerja->catatan : '';
                }
            }
            $this->show = TRUE;
            //$this->dispatchBrowserEvent('data_rencana', ['data_rencana' => $rencana_budaya_kerja]);
        }
    }
    public function updatedRencanaPenilaianId($va){
        $this->reset(['data_siswa', 'aspek_budaya_kerja', 'nilai', 'deskripsi']);
        if($this->rencana_penilaian_id){
            $this->opsi_budaya_kerja = Opsi_budaya_kerja::where('opsi_id', '<>', 1)->orderBy('updated_at', 'ASC')->get();
            $this->data_siswa = Peserta_didik::select('peserta_didik_id', 'nama', 'nisn')->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            })->with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with(['nilai_budaya_kerja' => function($query){
                    $query->whereHas('aspek_budaya_kerja', function($query){
                        $query->whereHas('rencana_budaya_kerja', function($query){
                            $query->where('rencana_budaya_kerja_id', $this->rencana_penilaian_id);
                        });
                    });
                }, 'catatan_budaya_kerja']);
            }])->orderBy('nama')->get();
            $this->show = TRUE;
            $this->aspek_budaya_kerja = Aspek_budaya_kerja::with(['elemen_budaya_kerja'])->where('rencana_budaya_kerja_id', $this->rencana_penilaian_id)->get();
            foreach($this->data_siswa as $siswa){
                foreach($siswa->anggota_rombel->nilai_budaya_kerja as $nilai_budaya_kerja){
                    $this->nilai[$siswa->anggota_rombel->anggota_rombel_id][$nilai_budaya_kerja->aspek_budaya_kerja_id] = $nilai_budaya_kerja->opsi_id.'|'.$nilai_budaya_kerja->elemen_id;
                    $this->deskripsi[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->catatan_budaya_kerja) ? $siswa->anggota_rombel->catatan_budaya_kerja->catatan : '';
                }
            }
        }
    }
    public function store(){
        foreach($this->nilai as $anggota_rombel_id => $nilai_p5){
            if(isset($this->deskripsi[$anggota_rombel_id])){
                Catatan_budaya_kerja::updateOrCreate(
                    [
                        'sekolah_id' => session('sekolah_id'),
                        'anggota_rombel_id' => $anggota_rombel_id,
                    ],
                    [
                        'catatan' => $this->deskripsi[$anggota_rombel_id]
                    ]
                );
            }
            foreach($nilai_p5 as $aspek_budaya_kerja_id => $nilai){
                $collection = Str::of($nilai)->explode('|');
                Nilai_budaya_kerja::updateOrCreate(
                    [
                        'sekolah_id' => session('sekolah_id'),
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'aspek_budaya_kerja_id' => $aspek_budaya_kerja_id,
                        'elemen_id' => $collection[1],
                    ],
                    [
                        'opsi_id' => $collection[0],
                    ]
                );
            }
        }
        $this->flash('success', 'Nilai berhasil disimpan', [], '/penilaian/projek-profil-pelajar-pancasila-dan-budaya-kerja');
    }
}
