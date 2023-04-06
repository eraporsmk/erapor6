<?php

namespace App\Http\Livewire\Laporan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Kenaikan_kelas;
use App\Models\Anggota_rombel;

class KenaikanKelas extends Component
{
    use LivewireAlert;
    public $show = FALSE;
    public $form = FALSE;
    public $tingkat;
    //public $data_siswa = [];
    public $status = [];
    public $nama_kelas = [];
    public $rombongan_belajar_id = [];
    protected $listeners = [
        //'confirmed' => '$refresh',
        'rombel',
    ];

    public function render()
    {
        if($this->check_walas()){
            $this->show = TRUE;
            $this->form = TRUE;
        }
        return view('livewire.laporan.kenaikan-kelas', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Kenaikan Kelas"]
            ],
            'data_siswa' => ($this->check_walas()) ? $this->getPD() : [],
            //'rombongan_belajar_id' => $this->check_walas()
        ]);
    }
    public function mount(){
        if($this->check_walas()){
            $data_siswa = $this->getPD();
            foreach($data_siswa as $siswa){
                $this->status[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_kenaikan_kelas) ? $siswa->anggota_rombel->single_kenaikan_kelas->status : '';
                $this->nama_kelas[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_kenaikan_kelas) ? $siswa->anggota_rombel->single_kenaikan_kelas->nama_kelas : '';
                $this->rombongan_belajar_id[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_kenaikan_kelas) ? $siswa->anggota_rombel->single_kenaikan_kelas->rombongan_belajar_id : '';
            }
        }
    }
    public function getPD(){
        $data = Peserta_didik::whereHas('anggota_rombel', function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('guru_id', $this->loggedUser()->guru_id);
                $query->where('jenis_rombel', 1);
            });
        })->with(['anggota_rombel' => function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('guru_id', $this->loggedUser()->guru_id);
                $query->where('jenis_rombel', 1);
            });
            $query->with(['single_kenaikan_kelas']);
        }])->orderBy('nama')->get();
        return $data;
    }
    public function loggedUser(){
        return auth()->user();
    }
    private function check_walas(){
        if($this->loggedUser()->hasRole('wali', session('semester_id'))){
            $rombel = Rombongan_belajar::where(function($query){
                $query->where('guru_id', $this->loggedUser()->guru_id);
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('semester_id', session('semester_aktif'));
                $query->where('jenis_rombel', 1);
            })->first();
            $next_rombel = NULL;
            if($rombel){
                if($rombel->tingkat == 12){
                    $next_rombel = Rombongan_belajar::where(function($query) use ($rombel){
                        $query->where('semester_id', session('semester_aktif'));
                        $query->where('sekolah_id', session('sekolah_id'));
                        $query->where('tingkat', ($rombel->tingkat + 1));
                    })->first();
                }
            }
            if($next_rombel){
                $this->tingkat = $next_rombel->tingkat;
            } else {
                $this->tingkat = ($rombel) ? $rombel->tingkat : 0;
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function store(){
        foreach($this->status as $anggota_rombel_id => $status){
            if($this->rombongan_belajar_id[$anggota_rombel_id]){
                Kenaikan_kelas::updateOrCreate(
                    [
                        'anggota_rombel_id' => $anggota_rombel_id,
                    ],
                    [
                        'sekolah_id' => session('sekolah_id'),
                        'rombongan_belajar_id' => $this->rombongan_belajar_id[$anggota_rombel_id],
                        'status' => $this->status[$anggota_rombel_id],
                        'nama_kelas' => $this->nama_kelas[$anggota_rombel_id],
                        'last_sync' => now(),
                    ]
                );
            }
        }
        $text = 'Kenaikan Kelas';
        if($this->tingkat >= 12){
            $text = 'Kelulusan';
        }
        $this->alert('success', $text.' berhasil disimpan', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed' 
        ]);
    }
    public function rombel($data)
    {
        $find = Rombongan_belajar::find($data['value']);
        $this->rombongan_belajar_id[$data['data']['inputAttributes']['anggota_rombel_id']] = $find->rombongan_belajar_id;
        $this->nama_kelas[$data['data']['inputAttributes']['anggota_rombel_id']] = $find->nama;
    }
    public function updated($name, $value){
        if($value){
            $name = explode('.',$name);
            if($name[0] == 'status' && $value == 1){
                $this->alert('', 'Pilih Rombongan Belajar', [
                    'inputAttributes' => [
                        'anggota_rombel_id' => $name[1]
                    ],
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Pilih',
                    'onConfirmed' => 'rombel',
                    'input' => 'select',
                    'inputOptions' => $this->get_next_rombel(),
                    'inputPlaceholder' => 'Pilih Rombongan Belajar',
                    'inputValidator' => '(value) => {
                        return new Promise((resolve) => {
                            if (value) {
                                resolve()
                            } else {
                                resolve("Rombongan Belajar tidak boleh kosong!")
                            }
                        })
                    }',    
                    'allowOutsideClick' => false,
                    'timer' => null
                ]);
            } else {
                $find = Anggota_rombel::with(['rombongan_belajar'])->find($name[1]);
                $this->rombongan_belajar_id[$name[1]] = $find->rombongan_belajar->rombongan_belajar_id;
                //$this->nama_kelas[$name[1]] = $find->rombongan_belajar->nama;
            }
        }
    }
    public function get_next_rombel(){
		$now = Rombongan_belajar::where(function($query){
            $query->where('semester_id', session('semester_aktif'));
            $query->where('sekolah_id', session('sekolah_id'));
            $query->where('guru_id', $this->loggedUser()->guru_id);
        })->first();
		$all_rombel = Rombongan_belajar::where(function($query) use ($now){
			$query->where('semester_id', session('semester_aktif'));
            $query->where('sekolah_id', session('sekolah_id'));
			$query->where('tingkat', ($now->tingkat + 1));
		})->get();
		if($all_rombel->count()){
            foreach($all_rombel as $rombel){
                $record[$rombel->rombongan_belajar_id] 	= $rombel->nama;   
            }
            $record[$now->rombongan_belajar_id] = 'Entry manual';		
            $output = $record;
        } else {
            $record[$now->rombongan_belajar_id] = 'Entry manual';
            $output= $record;
        }
		return $output;
	}
}
