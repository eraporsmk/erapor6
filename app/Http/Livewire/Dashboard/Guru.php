<?php

namespace App\Http\Livewire\Dashboard;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Pembelajaran;
use App\Models\Rombongan_belajar;
use App\Models\Nilai_remedial;
use App\Models\NilaiAkhirPengetahuan;
use App\Models\NilaiAkhirKeterampilan;
use App\Models\NilaiAkhirPk;
use App\Models\NilaiAkhirKurmer;
use App\Models\Nilai_akhir;
use App\Models\Nilai_rapor;
use App\Models\Nilai_sumatif;
class Guru extends Component
{
    use WithPagination, LivewireAlert;
    public $pembelajaran_id;
    public $kompentesi_id;

    protected $paginationTheme = 'bootstrap';
    public $nama_mata_pelajaran;
    public $search = '';
    public $per_page = 10;
    public $merdeka = FALSE;
    public $data_siswa = [];
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function loadPerPage(){
        $this->resetPage();
    }

    public function getListeners()
    {
        return [
            'confirmed' => '$refresh',
            'finishGenerate',
        ];
    }
    public function render()
    {
        $cara_penilaian = config('global.'.session('sekolah_id').'.'.session('semester_aktif').'.cara_penilaian');
        return view('livewire.dashboard.guru-'.$cara_penilaian, [
            'rombel_diampu' => Rombongan_belajar::whereHas('pembelajaran', function($query){
                $query->where($this->kondisi());
                /**/
            })->with(['pembelajaran' => function($query){
                $query->where($this->kondisi());
                /*$query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('guru_id', session('guru_id'));
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                $query->whereNull('induk_pembelajaran_id');
                $query->orWhere('guru_pengajar_id', session('guru_id'));
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->whereNotNull('kelompok_id');
                $query->whereNotNull('no_urut');
                $query->whereNull('induk_pembelajaran_id');*/
                $query->withCount([
                    'anggota_rombel',
                    'anggota_rombel as anggota_dinilai' => function($query){
                        $query->has('nilai_akhir_mapel');
                    },
                ]);
            }
            ])->with(['wali_kelas' => function($query){
                $query->select('guru_id', 'nama');
            }])->orderBy('tingkat')->get(),
            'rombongan_belajar' => ($this->loggedUser()->hasRole('wali', session('semester_id'))) ? Rombongan_belajar::with([
                'pembelajaran' => function($query){
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->with([
                        'guru' => function($query){
                            $query->select('guru_id', 'nama');
                        }, 
                        'pengajar' => function($query){
                            $query->select('guru_id', 'nama');
                        }
                    ]);
                    $query->withCount([
                        'anggota_rombel',
                    ]);
                },
                'kurikulum'
                ])->where(function($query){
                    $query->where('jenis_rombel', 1);
                    $query->where('guru_id', session('guru_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                })->first() : NULL,
            'rombel_pilihan' => ($this->loggedUser()->hasRole('wali', session('semester_id'))) ? Rombongan_belajar::with([
                'pembelajaran' => function($query){
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->with([
                        'guru' => function($query){
                            $query->select('guru_id', 'nama');
                        }, 
                        'pengajar' => function($query){
                            $query->select('guru_id', 'nama');
                        }
                    ]);
                    $query->withCount([
                        'anggota_rombel',
                    ]);
                },
                'kurikulum'
                ])->where(function($query){
                    $query->where('jenis_rombel', 16);
                    $query->where('guru_id', session('guru_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                })->first() : NULL,
            'collection' => ($this->loggedUser()->hasRole('waka', session('semester_id'))) ? Pembelajaran::where(function($query){
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->whereNull('induk_pembelajaran_id');
                })
                ->with(['rombongan_belajar'])
                ->withCount([
                    'anggota_rombel',
                ])
                ->when($this->search, function($query) {
                    $query->where('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->whereNull('induk_pembelajaran_id');
                    $query->orWhereHas('rombongan_belajar', function($query){
                        $query->whereIn('jenis_rombel', [1, 16]);
                        $query->where('semester_id', session('semester_aktif'));
                        $query->where('sekolah_id', session('sekolah_id'));
                        $query->where('nama', 'ILIKE', '%' . $this->search . '%');
                    });
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->whereNull('induk_pembelajaran_id');
                    $query->orWhereHas('guru', function($query){
                        $query->where('nama', 'ILIKE', '%' . $this->search . '%');
                    });
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->whereNull('induk_pembelajaran_id');
                    $query->orWhereHas('pengajar', function($query){
                        $query->where('nama', 'ILIKE', '%' . $this->search . '%');
                    });
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->whereNull('induk_pembelajaran_id');
                })
                ->paginate($this->per_page) : collect([]),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"]
            ],
        ]);
    }
    private function loggedUser(){
        return auth()->user();
    }
    private function kondisi(){
        return function($query){
            $query->where('semester_id', session('semester_aktif'));
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('guru_id', session('guru_id'));
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereNull('induk_pembelajaran_id');
            $query->orWhere('guru_pengajar_id', session('guru_id'));
            $query->where('semester_id', session('semester_aktif'));
            $query->where('sekolah_id', session('sekolah_id'));
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereNull('induk_pembelajaran_id');
        };
    }
    public function generateNilai($pembelajaran_id, $kompentesi_id){
        $this->pembelajaran_id = $pembelajaran_id;
        $this->kompetensi_id = $kompentesi_id;
        $pembelajaran = Pembelajaran::with(
            /*['rencana_penilaian' => function($query){
                $query->where('kompetensi_id', $this->kompetensi_id);
                $query->with(['kd_nilai' => function($query){
                    $query->with('nilai');
                }]);
            }]*/
            [
                'rombongan_belajar' => function($query){
                    $query->with(['anggota_rombel']);
                }
            ]
        )->find($this->pembelajaran_id);
        $emitted = 0;
        foreach($pembelajaran->rombongan_belajar->anggota_rombel as $anggota_rombel){
            $get_nilai_remedial = Nilai_remedial::where('pembelajaran_id', $this->pembelajaran_id)->where('anggota_rombel_id', $anggota_rombel->anggota_rombel_id)->where('kompetensi_id', $this->kompetensi_id)->first();
            $nilai_akhir = 0;
			if($get_nilai_remedial){
				$nilai_akhir = $get_nilai_remedial->rerata_remedial;
			} else {
                if($this->kompetensi_id == 1){
					$query = NilaiAkhirPengetahuan::where('pembelajaran_id', $this->pembelajaran_id)->where('anggota_rombel_id', $anggota_rombel->anggota_rombel_id)->where('kompetensi_id', $this->kompetensi_id)->first();
				} elseif($this->kompetensi_id == 2){
					$query = NilaiAkhirKeterampilan::where('pembelajaran_id', $this->pembelajaran_id)->where('anggota_rombel_id', $anggota_rombel->anggota_rombel_id)->where('kompetensi_id', $this->kompetensi_id)->first();
                } elseif($this->kompetensi_id == 3){
					$query = NilaiAkhirPk::where('pembelajaran_id', $this->pembelajaran_id)->where('anggota_rombel_id', $anggota_rombel->anggota_rombel_id)->where('kompetensi_id', $this->kompetensi_id)->first();
				} else {
					$query = NilaiAkhirKurmer::where('pembelajaran_id', $this->pembelajaran_id)->where('anggota_rombel_id', $anggota_rombel->anggota_rombel_id)->first();
				}
                $nilai_akhir = ($query) ? $query->nilai_akhir : 0;
                if($this->kompetensi_id == 4){
                    $nilai_sumatif = Nilai_sumatif::where('pembelajaran_id', $this->pembelajaran_id)->where('anggota_rombel_id', $anggota_rombel->anggota_rombel_id)->first();
                    if($nilai_sumatif){
                        $nilai_akhir = bilangan_bulat(collect([$nilai_akhir, $nilai_sumatif->nilai])->avg());
                    }
                }
            }
            if($nilai_akhir){
                $find_nilai_akhir = Nilai_akhir::where('pembelajaran_id', $this->pembelajaran_id)->where('anggota_rombel_id', $anggota_rombel->anggota_rombel_id)->where('kompetensi_id', $this->kompetensi_id)->first();
                if($find_nilai_akhir){
                    $find_nilai_akhir->nilai = $nilai_akhir;
                    $find_nilai_akhir->last_sync = now();
                    $find_nilai_akhir->save();
                } else {
                    Nilai_akhir::create([
                        'sekolah_id'		=> session('sekolah_id'),
                        'pembelajaran_id'	=> $this->pembelajaran_id,
                        'anggota_rombel_id'	=> $anggota_rombel->anggota_rombel_id,
                        'kompetensi_id'		=> $this->kompetensi_id,
                        'nilai'				=> $nilai_akhir,
                        'last_sync'		=> now()
                    ]);
                }
                $find_nilai_rapor = Nilai_rapor::where('anggota_rombel_id', $anggota_rombel->anggota_rombel_id)->where('pembelajaran_id', $pembelajaran_id)->first();
                $kkm = get_kkm($pembelajaran->kelompok_id, $pembelajaran->kkm);
                if($find_nilai_rapor){
                    $rasio_p = ($pembelajaran->rasio_p) ? $pembelajaran->rasio_p : 50;
                    $rasio_k = ($pembelajaran->rasio_k) ? $pembelajaran->rasio_k : 50;
                    if($this->kompetensi_id == 1 || $this->kompetensi_id == 3){
                        $total_nilai = (($nilai_akhir * $rasio_p) + ($find_nilai_rapor->nilai_k * $rasio_k)) / 100;
                        $find_nilai_rapor->nilai_p = $nilai_akhir;
                        $find_nilai_rapor->rasio_p = $rasio_p;
                        $find_nilai_rapor->total_nilai = bilangan_bulat($total_nilai);
                    } else {
                        $total_nilai = (($nilai_akhir * $rasio_k) + ($find_nilai_rapor->nilai_p * $rasio_p)) / 100;
                        $find_nilai_rapor->nilai_k = $nilai_akhir;
                        $find_nilai_rapor->rasio_k = $rasio_k;
                        $find_nilai_rapor->total_nilai = bilangan_bulat($total_nilai);
                    }
                    $find_nilai_rapor->save();
                } else {
                    Nilai_rapor::create([
                        'anggota_rombel_id'	=> $anggota_rombel->anggota_rombel_id,
                        'pembelajaran_id'	=> $this->pembelajaran_id,
                        'sekolah_id' 		=> session('sekolah_id'),
                        //'nilai_p' => ($this->kompetensi_id == 1 || $this->kompentesi_id == 3) ? $nilai_akhir : NULL,
                        //'nilai_k' => ($this->kompetensi_id != 1 || $this->kompentesi_id != 3) ? $nilai_akhir : NULL,
                        'nilai_p' => ($this->kompetensi_id == 1) ? $nilai_akhir : NULL,
                        'nilai_k' => ($this->kompetensi_id == 2) ? $nilai_akhir : NULL,
                        'rasio_p' => $pembelajaran->rasio_p,
                        'rasio_k' => $pembelajaran->rasio_k,
                        'total_nilai' => $nilai_akhir,
                        'last_sync'			=> now()
                    ]);
                }
            }
            $emitted += $nilai_akhir;
        }
        $this->emit('finishGenerate', $emitted);
		/*$status['icon'] = 'success';
		$status['text'] = "$b siswa berhasil disimpan. $a siswa berhasil diperbaharui";
		$status['insert'] = $b;
		$status['update'] = $a;
		$status['title'] = 'Generate Nilai Selesai!';
		echo json_encode($status);*/
    }
    public function finishGenerate($nilai_akhir){
        if($this->kompetensi_id == 1){
            $nama_penilaian = 'Pengetahuan';
        } elseif($this->kompetensi_id == 2){
            $nama_penilaian = 'Keterampilan';
        } else {
            $nama_penilaian = 'SMK PK';
        }
        $title = ($nilai_akhir) ? 'berhasil' : 'gagal';
        $type = ($nilai_akhir) ? 'success' : 'error';
        $text = ($nilai_akhir) ? 'Nilai akhir berhasil disimpan' : 'Tidak ada nilai tersimpan';
        $this->alert($type, 'Generate Nilai '.$nama_penilaian.' '.$title, [
            'text' => $text,
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
    }
    public function detil($pembelajaran_id){
        $this->pembelajaran_id = $pembelajaran_id;
        $pembelajaran = Pembelajaran::with(['rombongan_belajar' => function($query){
            $query->with(['pd' => function($query){
                $query->orderBy('nama');
                $query->with([
                    'nilai_akhir_kurmer' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    'nilai_akhir_pengetahuan' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    'deskripsi_mapel' => function($query){
                        $query->where('pembelajaran_id', $this->pembelajaran_id);
                    },
                    'agama',
                ]);
            }]);
        }])->find($pembelajaran_id);
        $this->nama_mata_pelajaran = $pembelajaran->nama_mata_pelajaran;
        $this->data_siswa = $pembelajaran->rombongan_belajar->pd;
        $this->merdeka = Str::contains($pembelajaran->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka');
        $this->emit('show-detil');
    }
}
