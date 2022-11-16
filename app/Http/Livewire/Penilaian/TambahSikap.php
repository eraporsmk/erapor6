<?php

namespace App\Http\Livewire\Penilaian;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Nilai_sikap;
use App\Models\Sikap;
use Carbon\Carbon;

class TambahSikap extends Component
{
    use LivewireAlert;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $anggota_rombel_id;
    public $tanggal;
    public $sikap_id;
    public $opsi_sikap;
    public $uraian_sikap;
    public $data_rombongan_belajar;
    public $data_pd;
    public $show = FALSE;
    
    public function loggedUser(){
        return auth()->user();
    }
    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Penilaian'], ['name' => "Tambah Data Nilai Sikap"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $this->semester_id = session('semester_id');
        return view('livewire.penilaian.tambah-sikap', [
            'all_sikap' => Sikap::whereHas('sikap')->with('sikap')->orderBy('sikap_id')->get(),
            'placeholder' => Carbon::parse(now())->translatedFormat('d F Y'),
            'breadcrumbs' => $breadcrumbs
        ]);
    }
    public function updatedTingkat(){
        if($this->tingkat){
            $data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId(){
        $this->dispatchBrowserEvent('data_pd', ['data_pd' => []]);
        if($this->rombongan_belajar_id){
            $data_pd = Peserta_didik::whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            })->with(['anggota_rombel' => function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }])->orderBy('nama')->get();
            $this->dispatchBrowserEvent('data_pd', ['data_pd' => $data_pd]);
        }
    }
    public function showButton(){
        $this->show = TRUE;
    }
    public function store(){
        if(!$this->tanggal){
            $this->tanggal = now()->format('Y-m-d');
        }
        $insert_sikap = [
            'sekolah_id'		=> session('sekolah_id'),
            'guru_id'			=> $this->loggedUser()->guru_id,
            'anggota_rombel_id'	=> $this->anggota_rombel_id,
            'tanggal_sikap' 	=> $this->tanggal,
            'sikap_id'			=> $this->sikap_id,
            'opsi_sikap'		=> $this->opsi_sikap,
            'uraian_sikap'		=> $this->uraian_sikap,
            'last_sync'			=> now(),
        ];
        Nilai_sikap::create($insert_sikap);
        $this->flash('success', 'Nilai Sikap berhasil disimpan', [], '/penilaian/sikap');
    }
}
