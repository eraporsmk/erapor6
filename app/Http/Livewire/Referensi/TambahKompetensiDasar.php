<?php

namespace App\Http\Livewire\Referensi;

use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Kompetensi_dasar;

class TambahKompetensiDasar extends Component
{
    public $semester_id;
    public $tingkat;
    public $rombongan_belajar_id;
    public $mata_pelajaran_id;
    public $kompetensi_id;
    public $id_kompetensi;
    public $kompetensi_dasar;
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
        'kompetensi_id' => 'required',
        'id_kompetensi' => 'required',
        'kompetensi_dasar' => 'required',
    ];

    protected $messages = [
        'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!!',
        'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!!',
        'mata_pelajaran_id.required' => 'Mata Pelajaran tidak boleh kosong!!',
        'kompetensi_id.required' => 'Aspek Penilaian tidak boleh kosong!!',
        'id_kompetensi.required' => 'Kode Kompetensi Dasar tidak boleh kosong!!',
        'kompetensi_dasar.required' => 'Deskripsi Kompetensi Dasar tidak boleh kosong!!',
    ];
    public function loggedUser(){
        return auth()->user();
    }
    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.referensi.tambah-kompetensi-dasar', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Tambah Data Kompetensi Dasar"]
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
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
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
                    $query->where('nama_kurikulum', 'ILIKE', '%REV%');
                });
            });
        };
    }
    public function changeRombel(){
        $this->data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
    }
    public function store(){
        $this->validate();
        $rombel = Rombongan_belajar::select('rombongan_belajar_id', 'kurikulum_id')->with(['kurikulum' => function($query){
            $query->select('kurikulum_id', 'nama_kurikulum');
        }])->find($this->rombongan_belajar_id);
        $kurikulum = $this->kurikulum($rombel->kurikulum->nama_kurikulum);
        Kompetensi_dasar::create([
            'kompetensi_dasar_id' => Str::uuid(),
            'id_kompetensi' => $this->id_kompetensi,
            'kompetensi_id' => $this->kompetensi_id,
            'mata_pelajaran_id' => $this->mata_pelajaran_id,
            'kelas_10' => ($this->tingkat == 10) ? 1 : 0,
            'kelas_11' => ($this->tingkat == 11) ? 1 : 0,
            'kelas_12' => ($this->tingkat == 12) ? 1 : 0,
            'kelas_13' => ($this->tingkat == 13) ? 1 : 0,
            'aktif'				=> 1,
			'kurikulum'			=> $kurikulum,
            'kompetensi_dasar' => $this->kompetensi_dasar,
            'user_id' => $this->loggedUser()->user_id,
            'last_sync' => now(),
        ]);
        session()->flash('message', 'Data KD/CP Berhasil disimpan');
        return redirect()->to('/referensi/kompetensi-dasar');
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
