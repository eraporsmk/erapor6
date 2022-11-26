<?php

namespace App\Http\Livewire\Dashboard;

use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use App\Models\Sekolah;
use App\Models\Status_penilaian;
use App\Models\Tujuan_pembelajaran;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Admin extends Component
{
    use LivewireAlert;
    public $sekolah;
    public $ptk = 0;
    public $pd = 0;
    public $rp = 0;
    public $rk = 0;
    public $np = 0;
    public $nk = 0;
    public $tp = 0;
    public $status;
    public $status_penilaian = FALSE;
    public $status_penilaian_selected;
    public function getListeners()
    {
        return [
            'confirmed',
        ];
    }
    public function render()
    {
        $cara_penilaian = config('global.'.session('sekolah_id').'.'.session('semester_aktif').'.cara_penilaian');
        $this->status = Status_penilaian::firstOrCreate(
			[
				'sekolah_id' => session('sekolah_id'),
				'semester_id' => session('semester_aktif'),
			],
			['status' => 1]
		);
        $this->tp = Tujuan_pembelajaran::whereHas('cp', function($query){
            $query->whereHas('pembelajaran', function($query){
                $query->where('sekolah_id', session('sekolah_id'));
            });
        })->count();
        $this->status_penilaian = ($this->status->status) ? TRUE: FALSE;
        $this->sekolah = Sekolah::withCount([
            'ptk' => function($query){
                $query->where('is_dapodik', 1);
                if(Schema::hasTable('ptk_keluar')){
                    $query->whereDoesntHave('ptk_keluar', function($query){
                        $query->where('semester_id', session('semester_aktif'));
                    });
                }
            },
            'rombongan_belajar' => function($query){
                $query->where('semester_id', session('semester_aktif'));
            },
            'pd_aktif',
            'rencana_pengetahuan' => function($query){
                $query->where('semester_id', session('semester_aktif'));
            },
            'rencana_keterampilan' => function($query){
                $query->where('semester_id', session('semester_aktif'));
            },
            'nilai_pengetahuan',
            'nilai_keterampilan',
            'nilai_akhir' => function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                });
            },
            'cp' => function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                });
            },
            'nilai_projek' => function($query){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                });
            }
        ])->find(session('sekolah_id'));
        return view('livewire.dashboard.admin-'.$cara_penilaian, [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"]
            ]
        ]);
    }
    public function gantiStatus(){
        $this->status_penilaian_selected = $this->status_penilaian;
        $text = ($this->status_penilaian) ? 'Penilaian akan di aktifkan' : 'Penilaian akan di nonaktifkan';
        $this->alert('warning', 'Anda Yakin?', [
            'text' => $text,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Ya',
            'showCancelButton' => true,
            'cancelButtonText' => 'Batal',
            'onConfirmed' => 'confirmed',
            'onDismissed' => 'cancelled',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
    }
    public function confirmed(){
        $this->status->status = ($this->status_penilaian_selected) ? 1 : 0;
        $this->status->save();
    }
}
