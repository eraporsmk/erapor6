<?php

namespace App\Http\Livewire\Penilaian;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Ekstrakurikuler;
use App\Models\Peserta_didik;
use App\Models\Rombongan_belajar;
use App\Models\Nilai_ekstrakurikuler;

class Ekskul extends Component
{
    use LivewireAlert;
    public $show = FALSE;
    public $semester_id;
    public $rombongan_belajar_id;
    public $data_siswa = [];
    public $rombongan_belajar_id_reguler;
    public $data_rombel = [];
    public $nilai_ekskul = [];
    public $deskripsi_ekskul;
    public $ekstrakurikuler;
    public $nilai_satuan;
    public $array = [];
    public $nama_ekskul;
    public $nama_rombel;

    protected $listeners = [
        'confirmed',
        'refresh',
    ];

    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Nilai Ekstrakurikuler"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $this->semester_id = session('semester_id');
        $this->array = collect([
            [
                'id' => 1,
                'name' => 'Sangat Baik'
            ],
            [
                'id' => 2,
                'name' => 'Baik',
            ],
            [
                'id' => 3,
                'name' => 'Cukup',
            ],
            [
                'id' => 4,
                'name' => 'Kurang',
            ]
        ]);
        return view('livewire.penilaian.ekskul', [
            'collection' => Ekstrakurikuler::where('guru_id', $this->loggedUser()->guru_id)->where('semester_id', session('semester_aktif'))->get(),
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function updatedRombonganBelajarId(){
        if($this->rombongan_belajar_id){
            $this->rombongan_belajar_id_reguler = NULL;
            $this->ekstrakurikuler = Ekstrakurikuler::where('rombongan_belajar_id', $this->rombongan_belajar_id)->first();
            $this->nama_ekskul = $this->ekstrakurikuler->nama_ekskul;
            $this->getPd();
            $this->show = TRUE;
            $this->data_rombel = Rombongan_belajar::where(function($query){
                $query->whereIn('rombongan_belajar_id',function($query){
                    $query->select('rombongan_belajar_id')->from('anggota_rombel');
                    $query->whereIn('peserta_didik_id',function($query){
                        $query->select('peserta_didik_id')->from('peserta_didik');
                        $query->whereIn('peserta_didik_id',function($query){
                            $query->select('peserta_didik_id')->from('anggota_rombel');
                            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                        });
                    });
                });
                $query->where('semester_id', session('semester_aktif'));
                $query->where('jenis_rombel', 1);
            })->orderBy('tingkat', 'ASC')->orderBy('kurikulum_id', 'ASC')->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $this->data_rombel]);
        }
    }
    public function updatedRombonganBelajarIdReguler(){
        $this->reset(['nilai_ekskul', 'data_siswa', 'deskripsi_ekskul']);
        if($this->rombongan_belajar_id_reguler){
            $rombel = Rombongan_belajar::find($this->rombongan_belajar_id_reguler);
            $this->nama_rombel = $rombel->nama;
        }
        $this->getPd();
    }
    public function getPd(){
        $this->data_siswa = Peserta_didik::where(function($query){
            if($this->rombongan_belajar_id_reguler){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('rombongan_belajar_id', $this->rombongan_belajar_id_reguler);
                });
                $query->whereIn('peserta_didik_id', function($query){
                    $query->select('peserta_didik_id')->from('anggota_rombel')->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                });
            } else {
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                });
                $query->whereHas('kelas', function($query){
                    $query->where('rombongan_belajar.semester_id', session('semester_aktif'));
                    $query->where('jenis_rombel', 1);
                });
            }
        })->with([
            'kelas' => function($query){
                $query->where('rombongan_belajar.semester_id', session('semester_aktif'));
                $query->where('jenis_rombel', 1);
            },
            'anggota_ekskul' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->with(['rombongan_belajar' => function($query){
                    $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                }]);
                /*$query->whereHas('rombongan_belajar', function($query){
                    $query->whereHas('kelas_ekskul', function($query){
                        $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                    });
                });*/
                $query->with(['single_nilai_ekstrakurikuler' => function($query){
                    $query->where('ekstrakurikuler_id', $this->ekstrakurikuler->ekstrakurikuler_id);
                }]);
            }
        ])->orderBy('nama')->get();
        foreach($this->data_siswa as $siswa){
            if($siswa->anggota_ekskul->single_nilai_ekstrakurikuler){
                $this->nilai_ekskul[$siswa->anggota_ekskul->anggota_rombel_id] = $siswa->anggota_ekskul->single_nilai_ekstrakurikuler->nilai;
                $this->deskripsi_ekskul[$siswa->anggota_ekskul->anggota_rombel_id] = $siswa->anggota_ekskul->single_nilai_ekstrakurikuler->deskripsi_ekskul;
            }
        }
    }
    public function updatedNilaiEkskul($value){
        $this->nilai_satuan = $value;
    }
    public function changeNilai($anggota_rombel_id){
        $this->getPd();
        $nilai = [
            1 => 'Sangat Baik',
            2 => 'Baik',
            3 => 'Cukup',
            4 => 'Kurang',
        ];
        $this->deskripsi_ekskul[$anggota_rombel_id] = '';
        if($this->nilai_satuan){
            $nama_ekskul = $this->ekstrakurikuler->nama_ekskul;
            $this->nilai_ekskul[$anggota_rombel_id] = $this->nilai_satuan;
            $this->deskripsi_ekskul[$anggota_rombel_id] = 'Melaksanakan kegiatan '.$nama_ekskul.' dengan '.$nilai[$this->nilai_satuan];
        } else {
            $this->deskripsi_ekskul[$anggota_rombel_id] = '';
            $this->nilai_ekskul[$anggota_rombel_id] = '';
        }
    }
    public function store(){
        foreach($this->nilai_ekskul as $anggota_rombel_id => $nilai){
            if($nilai){
                Nilai_ekstrakurikuler::updateOrCreate(
                    [
                        'anggota_rombel_id' => $anggota_rombel_id,
                        'sekolah_id' => session('sekolah_id'),
                        'ekstrakurikuler_id' => $this->ekstrakurikuler->ekstrakurikuler_id,
                    ],
                    [
                        'nilai' => $nilai,
                        'deskripsi_ekskul' => $this->deskripsi_ekskul[$anggota_rombel_id],
                        'last_sync' => now(),
                    ]
                );
            } else {
                Nilai_ekstrakurikuler::where('anggota_rombel_id', $anggota_rombel_id)->where('ekstrakurikuler_id', $this->ekstrakurikuler->ekstrakurikuler_id)->delete();
            }
        }
        $this->flash('success', 'Nilai Ekstrakurikuler berhasil disimpan', [], '/penilaian/ekstrakurikuler');
    }
    public function confirmed(){
        if(Nilai_ekstrakurikuler::where(function($query){
            $query->where('ekstrakurikuler_id', $this->ekstrakurikuler->ekstrakurikuler_id);
            if($this->rombongan_belajar_id_reguler){
                $query->whereHas('peserta_didik', function($query){
                    $query->whereHas('anggota_rombel', function($query){
                        $query->where('rombongan_belajar_id', $this->rombongan_belajar_id_reguler);
                    });
                });
            }
        })->delete()){
            $status = 'success';
            $header = 'Berhasil';
            if($this->nama_rombel){
                $text = 'Nilai Esktrakurikuler '.$this->nama_ekskul.' di Kelas '.$this->nama_rombel.' berhasil dihapus';
            } else {
                $text = 'Nilai Esktrakurikuler '.$this->nama_ekskul.' berhasil dihapus';
            }
        } else {
            $status = 'error';
            $header = 'Gagal';
            if($this->nama_rombel){
                $text = 'Tidak ada Nilai Esktrakurikuler '.$this->nama_ekskul.' di Kelas '.$this->nama_rombel.' dihapus';
            } else {
                $text = 'Tidak ada Nilai Esktrakurikuler '.$this->nama_ekskul.' dihapus';
            }
        }
        $this->getPd();
        $this->alert($status, $header, [
            'text' => $text,
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'refresh',
            'allowOutsideClick' => false,
        ]);
    }
    public function refresh(){
        $this->reset(['nilai_ekskul', 'data_siswa', 'deskripsi_ekskul', 'show', 'rombongan_belajar_id', 'rombongan_belajar_id_reguler']);
        $this->dispatchBrowserEvent('reset');
    }
    public function resetNilai()
    {
        $this->alert('question', 'Apakah Anda Yakin?', [
            'text' => 'Tindakan ini tidak dapat dikembalikan',
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yakin',
            'onConfirmed' => 'confirmed',
            'allowOutsideClick' => false,
            'showCancelButton' => true,
            'cancelButtonText' => 'Batal',
        ]);
    }
}
