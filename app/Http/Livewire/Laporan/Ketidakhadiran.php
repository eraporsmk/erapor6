<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Absensi;

class Ketidakhadiran extends Component
{
    use LivewireAlert;
    public $show = FALSE;
    public $form = FALSE;
    public $tingkat;
    public $data_rombongan_belajar = [];
    public $rombongan_belajar_id;
    public $data_siswa = [];
    public $sakit = [];
    public $izin = [];
    public $alpa = [];
    protected $listeners = [
        'confirmed' => '$refresh'
    ];

    public function render()
    {
        if($this->check_walas()){
            $this->show = TRUE;
            $this->form = TRUE;
        }
        return view('livewire.laporan.ketidakhadiran', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], 
                ['link' => '#', 'name' => 'Laporan'], 
                ['name' => "Ketidakhadiran"]
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
            $this->data_siswa = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 1);
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                });
            })->with(['anggota_rombel' => function($query){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 1);
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                });
                $query->with(['absensi']);
            }])->orderBy('nama')->get();
            foreach($this->data_siswa as $siswa){
                $this->sakit[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->absensi) ? $siswa->anggota_rombel->absensi->sakit : '';
                $this->izin[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->absensi) ? $siswa->anggota_rombel->absensi->izin : '';
                $this->alpa[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->absensi) ? $siswa->anggota_rombel->absensi->alpa : '';
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
        foreach($this->sakit as $anggota_rombel_id => $sakit){
            Absensi::UpdateOrCreate(
				[
                    'anggota_rombel_id' => $anggota_rombel_id
                ],
				[
					'sekolah_id' => session('sekolah_id'),
					'sakit' 	=> ($this->sakit[$anggota_rombel_id]) ? $this->sakit[$anggota_rombel_id] : 0,
					'izin'		=> ($this->izin[$anggota_rombel_id]) ? $this->izin[$anggota_rombel_id] : 0,
					'alpa'		=> ($this->alpa[$anggota_rombel_id]) ? $this->alpa[$anggota_rombel_id] : 0,
					'last_sync'	=> now(),
				]
			);
        }
        $this->alert('success', 'Ketidakhadiran berhasil disimpan', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed' 
        ]);
    }
}
