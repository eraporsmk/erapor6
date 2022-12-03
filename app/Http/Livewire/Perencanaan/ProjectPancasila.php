<?php

namespace App\Http\Livewire\Perencanaan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Budaya_kerja;
use App\Models\Rencana_penilaian;
use App\Models\Rombongan_belajar;
use App\Models\Pembelajaran;
use App\Models\Rencana_budaya_kerja;
use App\Models\Aspek_budaya_kerja;
use App\Models\Nilai_budaya_kerja;

class ProjectPancasila extends Component
{
    use WithPagination, LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function loadPerPage(){
        $this->resetPage();
    }
    public $sortby = 'created_at';
    public $sortbydesc = 'DESC';
    public $per_page = 10;
    public $nama = 'P5';
    public $kompetensi_id = 1;
    public $tingkat;
    public $rombongan_belajar_id;
    public $pembelajaran_id;
    public $semester_id;
    public $budaya_kerja = [];
    public $show = FALSE;

    public $nama_projek;
    public $deskripsi;
    public $sub_elemen = [];
    public $projek;
    public function render()
    {
        $this->semester_id = session('semester_id');
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Perencanaan'], ['name' => "Projek Penguatan Profil Pelajar Pancasila"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $callback = function($query){
			$query->with(['rombongan_belajar' => function($query){
                $query->select('rombongan_belajar_id', 'nama');
            }]);
            $query->where('sekolah_id', session('sekolah_id'));
			$query->where('semester_id', session('semester_aktif'));
            $query->whereHas('induk', function($query){
                $query->where('guru_id', $this->loggedUser()->guru_id);
                $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            });
		};
        return view('livewire.perencanaan.project-pancasila', [
            'breadcrumbs' => $breadcrumbs,
            'tombol_add' => [
                'wire' => 'addModal',
                'color' => 'primary',
                'text' => 'Tambah Data',
            ],
            //'budaya_kerja' => Budaya_kerja::orderBy('budaya_kerja_id')->get(),
            'collection' => Rencana_budaya_kerja::with(['pembelajaran' => $callback])->where(function($query) use ($callback){
                $query->whereHas('pembelajaran', $callback);
            })
            ->withCount('aspek_budaya_kerja')
            ->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($query) {
                    $query->where('nama_penilaian', 'ILIKE', '%' . $this->search . '%')
                    //->orWhere('pembelajaran.nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                    ->orWhereIn('pembelajaran_id', function($query){
                        $query->select('pembelajaran_id')
                        ->from('pembelajaran')
                        ->whereNotNull('kelompok_id')
                        ->whereNotNull('no_urut')
                        ->where('sekolah_id', session('sekolah_id'))
                        ->where('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                    });
            })->paginate($this->per_page),
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function addModal(){
        $this->emit('showModal');
    }
    public function updatedTingkat($value)
    {
        $this->reset(['rombongan_belajar_id', 'pembelajaran_id', 'show']);
        if($value){
            $this->data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->whereHas('pembelajaran', $this->kondisi());
            })->get();
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $this->data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId($value){
        $this->reset(['pembelajaran_id', 'show']);
        if($value){
            $this->data_pembelajaran = Pembelajaran::where($this->kondisi())->orderBy('mata_pelajaran_id', 'asc')->get();
            $this->dispatchBrowserEvent('data_pembelajaran', ['data_pembelajaran' => $this->data_pembelajaran]);
        }
    }
    public function updatedPembelajaranId($value){
        $this->reset(['show']);
        if($value){
            $this->budaya_kerja = Budaya_kerja::get();
            $this->show = TRUE;
        }
    }
    private function kondisi(){
        return function($query){
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            }
            $query->whereHas('induk', function($query){
                $query->where('guru_id', $this->loggedUser()->guru_id);
                $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            });
            //$query->where('guru_id', $this->loggedUser()->guru_id);
            //$query->whereNotNull('induk_pembelajaran_id');
            //$query->has('tema');
            //$query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
            //$query->whereNotNull('induk_pembelajaran_id');
            //$query->has('tema');
            //if($this->rombongan_belajar_id){
                //$query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            //}
        };
    }
    public function store(){
        $this->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'pembelajaran_id' => 'required',
                'nama_projek' => 'required',
                'deskripsi' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!!',
                'pembelajaran_id.required' => 'Tema tidak boleh kosong!!',
                'nama_projek.required' => 'Nama Projek tidak boleh kosong!!',
                'deskripsi.required' => 'Deskripsi Projek tidak boleh kosong!!',
            ]
        );
        //Rencana_budaya_kerja
        //use App\Models\Aspek_budaya_kerja;
        //use App\Models\Nilai_budaya_kerja;
        $rencana = Rencana_budaya_kerja::create([
            'sekolah_id' => session('sekolah_id'),
            'rombongan_belajar_id' => $this->rombongan_belajar_id,
            'pembelajaran_id' => $this->pembelajaran_id,
            'nama' => $this->nama_projek,
            'deskripsi' => $this->deskripsi,
        ]);
        foreach($this->sub_elemen as $key => $sub_elemen){
            $collection = Str::of($sub_elemen)->explode('|');
            Aspek_budaya_kerja::create([
                'sekolah_id' => session('sekolah_id'),
                'rencana_budaya_kerja_id' => $rencana->rencana_budaya_kerja_id,
                'budaya_kerja_id' => $collection[0],
                'elemen_id' => $collection[1],
            ]);
        }
        $this->flash('success', 'Rencana P5 berhasil disimpan', [], '/perencanaan/projek-profil-pelajar-pancasila-dan-budaya-kerja');
    }
    public function getID($id, $aksi){
        $this->reset(['nama_projek', 'deskripsi']);
        $this->projek = Rencana_budaya_kerja::find($id);
        if($aksi == 'detil'){
            $this->emit('detilModal');
        } elseif($aksi == 'edit'){
            $this->nama_projek = $this->projek->nama;
            $this->deskripsi = $this->projek->deskripsi;
            $this->emit('editModal');
        } else {
            $this->emit('deleteModal');
        }
        //$this->alert('error', 'Sedang dalam pengembangan!');
    }
    public function hapus(){
        $projek = $this->projek;
        if($projek && $projek->delete()){
            $this->emit('close-modal');
            $this->alert('success', 'Data Projek P5 berhasil dihapus!');
        } else {
            $this->alert('error', 'Data Projek P5 gagal dihapus!');
        }
        $this->reset(['projek', 'nama_projek', 'deskripsi']);
    }
    public function perbaharui(){
        $projek = $this->projek;
        $projek->nama = $this->nama_projek;
        $projek->deskripsi = $this->deskripsi;
        if($projek->save()){
            $this->emit('close-modal');
            $this->alert('success', 'Data Projek P5 berhasil diperbaharui!');
        } else {
            $this->alert('error', 'Data Projek P5 gagal diperbaharui!');
        }
    }
}
