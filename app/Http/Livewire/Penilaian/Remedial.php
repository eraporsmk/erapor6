<?php

namespace App\Http\Livewire\Penilaian;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Rencana_penilaian;
use App\Models\Anggota_rombel;
use App\Models\Peserta_didik;
use App\Models\Kd_nilai;
use App\Models\Nilai;
use App\Models\Nilai_remedial;

class Remedial extends Component
{
    use LivewireAlert;
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
    public $skm;
    //inject
    public $kompetensi_dasar_id;
    public $class_input = [];

    public function getListeners()
    {
        return [
            'confirmed'
        ];
    }
    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Penilaian'], ['name' => "Remedial"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $this->semester_id = session('semester_id');
        return view('livewire.penilaian.remedial', [
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
    public function changeRombel(){
        if($this->rombongan_belajar_id){
            $this->data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
        } else {
            $this->data_pembelajaran = NULL;
        }
        $this->mata_pelajaran_id = NULL;
    }
    public function changePembelajaran(){
        $this->reset(['skm']);
        if($this->mata_pelajaran_id){
            $pembelajaran = Pembelajaran::where('rombongan_belajar_id', $this->rombongan_belajar_id)->where('mata_pelajaran_id', $this->mata_pelajaran_id)->first();
            $this->pembelajaran_id = $pembelajaran->pembelajaran_id;
            $this->skm = get_kkm($pembelajaran->kelompok_id, 0);
        } else {
            $this->data_rencana = NULL;
            $this->pembelajaran_id = NULL;
        }
        $this->kompetensi_id = NULL;
    }
    public function changeKompetensi(){
        //$this->kompetensi_id = 'readonly';
        if($this->kompetensi_id == 1){
            $with_1 = 'nilai_kd_pengetahuan';
            $with_2 = 'v_nilai_akhir_p';
        } elseif($this->kompetensi_id == 2){
            $with_1 = 'nilai_kd_keterampilan';
            $with_2 = 'v_nilai_akhir_k';
        } else {
            $with_1 = 'nilai_kd_pk';
            $with_2 = 'v_nilai_akhir_pk';
        }
        $callback = function($query) use ($with_1, $with_2){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            $query->with([
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
                }
            ]);
        };
        $get_mapel_agama = filter_agama_siswa($this->pembelajaran_id, $this->rombongan_belajar_id);
        $this->data_siswa = Peserta_didik::where(function($query) use ($callback, $get_mapel_agama){
            $query->whereHas('anggota_rombel', $callback);
            if($get_mapel_agama){
                $query->where('agama_id', $get_mapel_agama);
            }
        })->with(['anggota_rombel' => $callback])->orderBy('nama')->get();
        $callback = function($query){
			$query->with('pembelajaran');
			$query->where('kompetensi_id', $this->kompetensi_id);
			$query->where('pembelajaran_id', $this->pembelajaran_id);
		};
        $this->kd_nilai = Kd_nilai::whereHas('rencana_penilaian', $callback)->with(['rencana_penilaian' => $callback, 'kompetensi_dasar'])->select(['kompetensi_dasar_id', 'id_kompetensi'])->groupBy(['kompetensi_dasar_id', 'id_kompetensi'])->orderBy('id_kompetensi')->get();
        foreach($this->data_siswa as $data_siswa){
            $rerata = [];
            //dd($data_siswa->anggota_rombel);
            foreach($data_siswa->anggota_rombel->{$with_1} as $nilai_kd){
                $rerata[$data_siswa->anggota_rombel->anggota_rombel_id][] = ($nilai_kd->nilai_kd) ? $nilai_kd->nilai_kd : 0;
                if($data_siswa->anggota_rombel->nilai_remedial){
                    $nilai_remedial = unserialize($data_siswa->anggota_rombel->nilai_remedial->nilai);
                    foreach($nilai_remedial as $kompetensi_dasar_id => $nilai_perkd){
                        $this->nilai[$data_siswa->anggota_rombel->anggota_rombel_id][$kompetensi_dasar_id] = $nilai_perkd;
                    }
                } else {
                    $this->nilai[$data_siswa->anggota_rombel->anggota_rombel_id][$nilai_kd->kompetensi_dasar_id] = $nilai_kd->nilai_kd;
                }
            }
            if(isset($rerata[$data_siswa->anggota_rombel->anggota_rombel_id])){
                $nilai_rerata = bilangan_bulat(collect($rerata[$data_siswa->anggota_rombel->anggota_rombel_id])->avg());
            } else {
                $nilai_rerata = 0;
            }
            $this->rerata[$data_siswa->anggota_rombel->anggota_rombel_id] = $nilai_rerata;
            $this->remedial[$data_siswa->anggota_rombel->anggota_rombel_id] = ($data_siswa->anggota_rombel->nilai_remedial) ? $data_siswa->anggota_rombel->nilai_remedial->rerata_remedial : 0;
            foreach($this->kd_nilai as $kd){
                $this->kompetensi_dasar_id[$kd->kompetensi_dasar_id] = $kd->kompetensi_dasar_id;
            }
            $this->class_input[$data_siswa->anggota_rombel->anggota_rombel_id] = 'bg-success';
        }
        $this->show = TRUE;
    }
    public function store(){
        $kompetensi_dasar_id = collect($this->kompetensi_dasar_id);
        foreach($this->nilai as $anggota_rombel_id => $nilai_remedial){
            $collection = collect($nilai_remedial);
            $filtered = $collection->filter(function ($value, $key) use ($kompetensi_dasar_id){
                return $kompetensi_dasar_id->contains($key);
            });
            Nilai_remedial::updateOrCreate(
                [
                    'anggota_rombel_id' => $anggota_rombel_id,
                    'pembelajaran_id' => $this->pembelajaran_id,
                    'kompetensi_id' => $this->kompetensi_id,
                ],
                [
                    'sekolah_id' => session('sekolah_id'),
                    'nilai' => serialize($filtered->all()),
                    'rerata_akhir' => 0,
                    'rerata_remedial' => bilangan_bulat($filtered->avg()),
                    //bilangan_bulat(array_sum($nilai_remedial)/count($nilai_remedial)),
                ]
            );
        }
        $this->flash('success', 'Nilai Remedial berhasil disimpan', [], '/penilaian/remedial');
    }
    public function updatedNilai($value, $key)
    {
        $callback = function($query){
			$query->with('pembelajaran');
			$query->where('kompetensi_id', $this->kompetensi_id);
			$query->where('pembelajaran_id', $this->pembelajaran_id);
		};
        $this->kd_nilai = Kd_nilai::whereHas('rencana_penilaian', $callback)->with(['rencana_penilaian' => $callback, 'kompetensi_dasar'])->select(['kompetensi_dasar_id', 'id_kompetensi'])->groupBy(['kompetensi_dasar_id', 'id_kompetensi'])->orderBy('id_kompetensi')->get();
        $key = explode('.', $key);
        if($value >= 1 && $value <= 100){
            $this->nilai[$key[0]][$key[1]] = number_format($value, 0);
            $nilai_remedial = $this->nilai[$key[0]];
            $this->remedial[$key[0]] = (array_filter($nilai_remedial)) ? bilangan_bulat(collect(array_filter($nilai_remedial))->avg()) : 0;
        } else {
            $this->nilai[$key[0]][$key[1]] = 0;
        }
        //$this->class_input[$key[0]] = 'bg-success1';
        //bilangan_bulat(array_sum($nilai_remedial)/count($nilai_remedial));
        //dd($this->nilai[$key[0]]);
    }
    public function hapusRemedial($anggota_rombel_id){
        $this->changeKompetensi();
        $this->anggota_rombel_id = $anggota_rombel_id;
        $this->alert('question', 'Apakah Anda yakin?', [
            'text' => 'Tindakan ini tidak dapat dikembalikan!',
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Batal',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
    }
    public function confirmed(){
        $delete = Nilai_remedial::where(function($query){
            $query->where('anggota_rombel_id', $this->anggota_rombel_id);
            $query->where('pembelajaran_id', $this->pembelajaran_id);
        })->delete();
        if($delete){
            $this->alert('success', 'Nilai Remedian berhasil dihapus', [
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'terkonfirmasi',
                'allowOutsideClick' => true,
                'timer' => null
            ]);
        } else {
            $this->alert('error', 'Nilai Remedian gagal dihapus. Silahkan coba beberapa saat lagi!', [
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'terkonfirmasi',
                'allowOutsideClick' => true,
                'timer' => null
            ]);
        }
        $this->changeKompetensi();
    }
}
