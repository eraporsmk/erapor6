<?php

namespace App\Http\Livewire\WaliKelas;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Peserta_didik;
use App\Models\Budaya_kerja;
use App\Models\Catatan_budaya_kerja;
use App\Models\Rombongan_belajar;

class CatatanSikap extends Component
{
    use LivewireAlert;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $show = FALSE;
    public $form = FALSE;
    public $uraian_sikap = [];
    public $data_siswa = [];
    public $budaya_kerja = [];
    public $merdeka = FALSE;
    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.wali-kelas.catatan-sikap', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Catatan Sikap"]
            ]
        ]);
    }
    public function mount(){
        $this->budaya_kerja = Budaya_kerja::with(['elemen_budaya_kerja'])->get();
        if(\Laratrust::hasRole('waka', session('semester_id'))){
            $this->show = FALSE;
            $this->form = FALSE;
        } elseif($this->check_walas()){
            $this->show = TRUE;
            $this->form = TRUE;
            $this->getPd();
            $this->rombongan_belajar = Rombongan_belajar::with([
                'kurikulum'
                ])->where(function($query){
                    $query->where('jenis_rombel', 1);
                    $query->where('guru_id', session('guru_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                })->first();
            $this->rombongan_belajar_id = $this->rombongan_belajar->rombongan_belajar_id;
            $this->merdeka = Str::contains($this->rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka');
        }
    }
    public function updatedTingkat(){
        $this->reset(['show', 'form', 'data_siswa', 'rombongan_belajar_id', 'merdeka']);
        if($this->tingkat){
            $data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('jenis_rombel', 1);
                /*$query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });*/
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId(){
        $this->reset(['show', 'form', 'data_siswa', 'merdeka']);
        if($this->rombongan_belajar_id){
            $rombongan_belajar = Rombongan_belajar::with(['kurikulum'])->find($this->rombongan_belajar_id);
            $this->merdeka = Str::contains($rombongan_belajar->kurikulum->nama_kurikulum, 'Merdeka');
            $this->getPd();
        }
    }
    private function getPd(){
        $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            } else {
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('guru_id', session('guru_id'));
                });
            }
        })->with(['anggota_rombel' => function($query){
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            } else {
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('guru_id', session('guru_id'));
                });
            }
            $query->with([
                'nilai_budaya_kerja_guru' => function($query){
                    $query->with(['guru', 'budaya_kerja', 'elemen_budaya_kerja']);
                    $query->orderBy('budaya_kerja_id');
                    $query->orderBy('elemen_id');
                },
                'all_catatan_budaya_kerja' => function($query){
                    $query->whereNotNull('budaya_kerja_id');
                }
            ]);
        }])->orderBy('nama')->get();
        foreach($this->data_siswa as $siswa){
            foreach($siswa->anggota_rombel->all_catatan_budaya_kerja as $catatan){
                $this->uraian_sikap[$siswa->anggota_rombel->anggota_rombel_id][$catatan->budaya_kerja_id] = $catatan->catatan;
            }
        }
        $this->show = true;
        $this->form = $this->check_walas($this->rombongan_belajar_id);
    }
    public function updatedUraianSikap(){
        //
    }
    private function check_walas($rombongan_belajar_id = NULL){
        if($rombongan_belajar_id){
            $rombongan_belajar = Rombongan_belajar::where(function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            })->find($rombongan_belajar_id);
            if($rombongan_belajar && $rombongan_belajar->guru_id == session('guru_id')){
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if(\Laratrust::hasRole('wali', session('semester_id'))){
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    public function store(){
        foreach($this->uraian_sikap as $anggota_rombel_id => $data){
            foreach($data as $budaya_kerja_id => $uraian_sikap){
                if($anggota_rombel_id && $uraian_sikap){
                    Catatan_budaya_kerja::updateOrCreate(
                        [
                            'sekolah_id' => session('sekolah_id'),
                            'anggota_rombel_id' => $anggota_rombel_id,
                            'budaya_kerja_id' => $budaya_kerja_id,
                        ],
                        [
                            'catatan' => $uraian_sikap,
                            'last_sync' => now(),
                        ]
                    );
                }
            }
        }
        $this->flash('success', 'Catatan Sikap berhasil disimpan', [], '/wali-kelas/catatan-sikap');
    }
}
