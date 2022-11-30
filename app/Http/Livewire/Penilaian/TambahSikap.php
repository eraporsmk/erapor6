<?php

namespace App\Http\Livewire\Penilaian;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Catatan_budaya_kerja;
use App\Models\Budaya_kerja;
use App\Models\Elemen_budaya_kerja;
use App\Models\Nilai_budaya_kerja;
use Carbon\Carbon;

class TambahSikap extends Component
{
    use LivewireAlert;
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $anggota_rombel_id;
    public $tanggal;
    public $budaya_kerja_id;
    public $elemen_id;
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
            'all_sikap' => Budaya_kerja::with(['elemen_budaya_kerja'])->get(),
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
                $query->where('jenis_rombel', 1);
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
    public function updatedBudayaKerjaId(){
        $elemen = Elemen_budaya_kerja::where('budaya_kerja_id', $this->budaya_kerja_id)->get()->unique('elemen');
        $this->dispatchBrowserEvent('elemen', ['elemen' => $elemen]);
    }
    public function updatedOpsiSikap(){
        $this->show = TRUE;
    }
    public function store(){
        $this->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'anggota_rombel_id' => 'required',
                'tanggal' => 'required',
                'budaya_kerja_id' => 'required',
                'elemen_id' => 'required',
                'opsi_sikap' => 'required',
                'uraian_sikap' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!',
                'anggota_rombel_id.required' => 'Peserta Didik tidak boleh kosong!',
                'tanggal.required' => 'Tanggal tidak boleh kosong!',
                'budaya_kerja_id.required' => 'Dimensi Sikap tidak boleh kosong!',
                'elemen_id.required' => 'Elemen Sikap tidak boleh kosong!',
                'opsi_sikap.required' => 'Opsi Sikap tidak boleh kosong!',
                'uraian_sikap.required' => 'Uraian Sikap tidak boleh kosong!',
            ]
        );
        if(!$this->tanggal){
            $this->tanggal = now()->format('Y-m-d');
        }
        Nilai_budaya_kerja::create([
            'sekolah_id'		=> session('sekolah_id'),
            'guru_id' => $this->loggedUser()->guru_id,
            'anggota_rombel_id'	=> $this->anggota_rombel_id,
            'tanggal' 	=> $this->tanggal,
            'budaya_kerja_id'	=> $this->budaya_kerja_id,
            'elemen_id' => $this->elemen_id,
            'opsi_id'		=> $this->opsi_sikap,
            'deskripsi'		=> $this->uraian_sikap,
            'last_sync'			=> now(),
        ]);
        $this->flash('success', 'Nilai Sikap berhasil disimpan', [], '/penilaian/sikap');
    }
}
