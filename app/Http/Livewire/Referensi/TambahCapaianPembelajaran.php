<?php

namespace App\Http\Livewire\Referensi;

use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Capaian_pembelajaran;

class TambahCapaianPembelajaran extends Component
{
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $mata_pelajaran_id;
    public $elemen;
    public $capaian_pembelajaran;
    public $data_rombongan_belajar = [];
    public $data_pembelajaran = [];
    
    public function getListeners()
    {
        return [
            'changeTingkat',
            'changeRombel',
        ];
    }
    protected $rules = [
        'tingkat' => 'required',
        'rombongan_belajar_id' => 'required',
        'mata_pelajaran_id' => 'required',
        'elemen' => 'required',
        'capaian_pembelajaran' => 'required',
    ];

    protected $messages = [
        'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!!',
        'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!!',
        'mata_pelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong!!',
        'elemen.required' => 'Elemen tidak boleh kosong!!',
        'capaian_pembelajaran.required' => 'Capaian Pembelajaran tidak boleh kosong!!',
    ];
    public function loggedUser(){
        return auth()->user();
    }
    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.referensi.tambah-capaian-pembelajaran', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Tambah Data Capaian Pembelajaran"]
            ]
        ]);
    }
    public function changeTingkat(){
        $this->data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
            $query->where('tingkat', $this->tingkat);
            $query->where('semester_id', session('semester_aktif'));
            $query->where('sekolah_id', session('sekolah_id'));
            $query->whereHas('pembelajaran', $this->kondisi());
        })->get();
    }
    private function kondisi(){
        return function($query){
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->where('guru_id', $this->loggedUser()->guru_id);
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                });
            });
            $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->whereNotNull('kelompok_id');
            $query->whereNotNull('no_urut');
            $query->whereHas('rombongan_belajar', function($query){
                $query->whereHas('kurikulum', function($query){
                    $query->where('nama_kurikulum', 'ILIKE', '%Merdeka%');
                });
            });
        };
    }
    public function changeRombel(){
        $this->data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
    }
    public function store(){
        $this->validate();
        if($this->tingkat == 10){
            $fase = 'E';
        } else {
            $fase = 'F';
        }
        $last_id_ref = Capaian_pembelajaran::where('is_dir', 1)->count();
        $last_id_non_ref = Capaian_pembelajaran::where('is_dir', 0)->count();
        Capaian_pembelajaran::create([
            'cp_id' => ($last_id_non_ref) ? ($last_id_ref + $last_id_non_ref) + 1 : 1000,
            'mata_pelajaran_id' => $this->mata_pelajaran_id,
            'fase' => $fase,
            'elemen' => $this->elemen,
            'deskripsi' => $this->capaian_pembelajaran,
            'aktif'				=> 1,
			'last_sync' => now(),
        ]);
        session()->flash('message', 'Data Capaian Pembelajaran Berhasil disimpan');
        return redirect()->to('/referensi/capaian-pembelajaran');
    }
    private function kurikulum($string){
        if(Str::contains($string, 'REV')){
            $kurikulum = 2017;
        } elseif(Str::contains($string, 'KTSP')){
            $kurikulum = 2006;
        } elseif(Str::contains($string, 'Pusat')){
            $kurikulum = 2021;
        } else {
            $kurikulum = 2013;
        }
        return $kurikulum;
    }
}
