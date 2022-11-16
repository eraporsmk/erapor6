<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Catatan_wali;
use Erapor;

class CatatanAkademik extends Component
{
    use LivewireAlert;
    public $show = FALSE;
    public $form = FALSE;
    public $tingkat;
    public $data_rombongan_belajar = [];
    public $rombongan_belajar_id;
    public $data_siswa = [];
    public $catatan_akademik = [];
    protected $listeners = [
        'confirmed' => '$refresh'
    ];

    public function render()
    {
        if($this->check_walas()){
            $this->show = TRUE;
            $this->form = TRUE;
        }
        return view('livewire.laporan.catatan-akademik', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], 
                ['link' => '#', 'name' => 'Laporan'], 
                ['name' => "Catatan Akademik"]
            ]
        ]);
    }
    public function changeTingkat(){
        $this->reset(['data_rombongan_belajar', 'rombongan_belajar_id', 'data_siswa', 'catatan_akademik']);
        $this->data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
            $query->where('tingkat', $this->tingkat);
            $query->where('semester_id', session('semester_aktif'));
            $query->where('sekolah_id', session('sekolah_id'));
        })->get();
    }
    public function changeRombel(){
        $this->reset(['data_siswa', 'catatan_akademik']);
        $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->with(['anggota_rombel' => function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        }])->orderBy('nama')->get();
        foreach($this->data_siswa as $siswa){
            $this->catatan_akademik[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_catatan_wali) ? $siswa->anggota_rombel->single_catatan_wali->uraian_deskripsi : 'Belum ada catatan ';
        }
    }
    public function mount(){
        if($this->check_walas()){
            /*
            $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('guru_id', session('guru_id'));
                });
            })->with(['anggota_rombel' => function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('guru_id', session('guru_id'));
                });
                $query->with(['single_catatan_wali']);
            }])->orderBy('nama')->get();*/
            $with = ['single_catatan_wali'];
            $this->data_siswa = pd_walas($with);
            foreach($this->data_siswa as $siswa){
                $this->catatan_akademik[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_catatan_wali) ? $siswa->anggota_rombel->single_catatan_wali->uraian_deskripsi : '';
            }
        }
    }
    public function loggedUser(){
        return auth()->user();
    }
    private function check_walas(){
        if($this->loggedUser()->hasRole('wali', session('semester_id'))){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function store(){
        foreach($this->catatan_akademik as $anggota_rombel_id => $uraian_deskripsi){
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
        }
        $this->alert('success', 'Catatan Akademik berhasil disimpan', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed' 
        ]);
    }
}
