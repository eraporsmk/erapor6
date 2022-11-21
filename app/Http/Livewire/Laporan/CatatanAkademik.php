<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Catatan_wali;
use App\Models\Nilai_rapor;
use Erapor;

class CatatanAkademik extends Component
{
    use LivewireAlert;
    public $semester_id;
    public $tingkat;
    public $show = FALSE;
    public $form = FALSE;
    public $data_rombongan_belajar = [];
    public $rombongan_belajar_id;
    public $data_siswa = [];
    public $catatan_akademik = [];
    public $nilai_rapor = [];
    protected $listeners = [
        'confirmed' => '$refresh'
    ];

    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.laporan.catatan-akademik', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], 
                ['link' => '#', 'name' => 'Laporan'], 
                ['name' => "Catatan Akademik"]
            ]
        ]);
    }
    public function updatedTingkat(){
        $this->reset(['data_rombongan_belajar', 'rombongan_belajar_id', 'data_siswa', 'catatan_akademik']);
        if($this->tingkat){
            $data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('jenis_rombel', 1);
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId(){
        $this->reset(['data_siswa', 'catatan_akademik', 'show', 'nilai_rapor']);
        $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->with(['anggota_rombel' => function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        }])->orderBy('nama')->get();
        foreach($this->data_siswa as $siswa){
            $this->catatan_akademik[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_catatan_wali) ? $siswa->anggota_rombel->single_catatan_wali->uraian_deskripsi : 'Belum ada catatan ';
            $this->nilai_rapor[$siswa->anggota_rombel->anggota_rombel_id] = Nilai_rapor::with(['pembelajaran' => function($query){
                $query->select('pembelajaran_id', 'nama_mata_pelajaran');
            }])->where('anggota_rombel_id', $siswa->anggota_rombel->anggota_rombel_id)->orderBy('total_nilai', 'ASC')->take(3)->get();
        }
        $this->show = true;
        $this->form = $this->check_walas($this->rombongan_belajar_id);
    }
    public function updatedCatatanAkademik(){
        if($this->loggedUser()->hasRole('waka', session('semester_id'))){
            $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            })->with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }])->orderBy('nama')->get();
            foreach($this->data_siswa as $siswa){
                $this->nilai_rapor[$siswa->anggota_rombel->anggota_rombel_id] = Nilai_rapor::with(['pembelajaran' => function($query){
                    $query->select('pembelajaran_id', 'nama_mata_pelajaran');
                }])->where('anggota_rombel_id', $siswa->anggota_rombel->anggota_rombel_id)->orderBy('total_nilai', 'ASC')->take(3)->get();
            }
        } elseif($this->check_walas()){
            $with = ['single_catatan_wali'];
            $this->data_siswa = pd_walas($with);
            foreach($this->data_siswa as $siswa){
                $this->nilai_rapor[$siswa->anggota_rombel->anggota_rombel_id] = Nilai_rapor::with(['pembelajaran' => function($query){
                    $query->select('pembelajaran_id', 'nama_mata_pelajaran');
                }])->where('anggota_rombel_id', $siswa->anggota_rombel->anggota_rombel_id)->orderBy('total_nilai', 'ASC')->take(3)->get();
            }
        }
    }
    public function mount(){
        if($this->loggedUser()->hasRole('waka', session('semester_id'))){
            $this->show = FALSE;
            $this->form = FALSE;
        } elseif($this->check_walas()){
            $this->show = TRUE;
            $this->form = TRUE;
            $with = ['single_catatan_wali'];
            $this->data_siswa = pd_walas($with);
            foreach($this->data_siswa as $siswa){
                $this->catatan_akademik[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_catatan_wali) ? $siswa->anggota_rombel->single_catatan_wali->uraian_deskripsi : '';
                $this->nilai_rapor[$siswa->anggota_rombel->anggota_rombel_id] = Nilai_rapor::with(['pembelajaran' => function($query){
                    $query->select('pembelajaran_id', 'nama_mata_pelajaran');
                }])->where('anggota_rombel_id', $siswa->anggota_rombel->anggota_rombel_id)->orderBy('total_nilai', 'ASC')->take(3)->get();
            }
        }
    }
    public function loggedUser(){
        return auth()->user();
    }
    private function check_walas($rombongan_belajar_id = NULL){
        if($rombongan_belajar_id){
            $rombongan_belajar = Rombongan_belajar::find($rombongan_belajar_id);
            if($rombongan_belajar->guru_id == $this->loggedUser()->guru_id){
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            if($this->loggedUser()->hasRole('wali', session('semester_id'))){
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    public function store(){
        foreach($this->catatan_akademik as $anggota_rombel_id => $uraian_deskripsi){
            if($uraian_deskripsi){
                Catatan_wali::updateOrCreate(
                    [
                        'anggota_rombel_id' => $anggota_rombel_id,
                    ],
                    [
                        'sekolah_id' => session('sekolah_id'),
                        'uraian_deskripsi' => $uraian_deskripsi,
                        'last_sync' => now(),
                    ]
                );
            } else {
                Catatan_wali::where('anggota_rombel_id', $anggota_rombel_id)->delete();
            }
        }
        $this->flash('success', 'Catatan Akademik berhasil disimpan', [], '/laporan/catatan-akademik');
    }
}
