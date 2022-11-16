<?php

namespace App\Http\Livewire\Referensi;

use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Paket_ukk;
use App\Models\Unit_ukk;
use App\Models\Jurusan_sp;
use App\Models\Kurikulum;

use Helper;

class UjiKompetensiKeahlian extends Component
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
    public $sortby = 'nama';
    public $sortbydesc = 'ASC';
    public $per_page = 10;
    public $paket_ukk;
    public $jml = 5;
    //form
    public $nama_jurusan;
    public $kode_kompetensi;
    public $nomor_paket;
    public $judul_paket;
    public $collection_unit = [];
    public $paket_ukk_id;
    public $kode_unit = [];
    public $nama_unit = [];
    public $jurusan_id;
    public $kurikulum_id;
    public $all_nomor_paket = [];
    public $nama_paket_id = [];
    public $nama_paket_en = [];
    public $status = [];

    protected $listeners = ['postAdded' => 'incrementRow'];
    public function incrementRow(){
        $this->jml++;
    }
    public function render()
    {
        return view('livewire.referensi.uji-kompetensi-keahlian', [
            'collection' => Paket_ukk::where(function($query){
                $query->where('sekolah_id', session('sekolah_id'));
            })->with(['jurusan', 'unit_ukk'])
            ->orderBy('jurusan_id', 'asc')
            ->orderBy('kurikulum_id', 'asc')
            ->orderBy('nomor_paket', 'asc')
            ->when($this->search, function($query) {
                $query->where('nama_paket_id', 'ILIKE', '%' . $this->search . '%');
            })
            ->paginate($this->per_page),
            'all_jurusan' => Jurusan_sp::where(function($query){
                $query->where('sekolah_id', session('sekolah_id'));
                $query->whereHas('rombongan_belajar', function($query){
                    $query->whereIn('tingkat', [12, 13]);
                });
                $query->has('kurikulum');
            })->get(),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Uji Kompetensi Keahlian"]
            ],
            'tombol_add' => [
                'wire' => 'tambahModal',
                'color' => 'primary',
                'text' => 'Tambah Data',
            ],
        ]);
    }
    private function loggedUser(){
        return auth()->user();
    }
    public function getUnit($id, $aksi){
        $this->reset(['paket_ukk_id', 'paket_ukk', 'nama_jurusan', 'kode_kompetensi', 'nomor_paket', 'judul_paket', 'all_nomor_paket', 'nama_paket_id', 'nama_paket_en', 'status', 'collection_unit']);
        $this->paket_ukk = Paket_ukk::find($id);
        $this->paket_ukk_id = $id;
        $this->nama_jurusan = $this->paket_ukk->jurusan->nama_jurusan;
        $this->kode_kompetensi = $this->paket_ukk->kode_kompetensi;
        $this->nomor_paket = $this->paket_ukk->nomor_paket;
        $this->judul_paket = $this->paket_ukk->nama_paket_id;
        if($aksi == 'add'){
            $this->jml = 5;
            $this->emit('addModal');
        } elseif($aksi == 'detil'){
            $this->emit('detilModal');
        } elseif($aksi == 'status'){
            $paket_ukk = $this->paket_ukk;
            $status = $paket_ukk->status;
            $paket_ukk->status = ($paket_ukk->status) ? 0 : 1;
            $paket_ukk->save();
            $this->emit('close-modal');
            if($status){
                $this->alert('success', 'Berhasil', [
                    'text' => 'Data Paket UKK berhasil di Non Aktifkan!'
                ]);
            } else {
                $this->alert('success', 'Berhasil', [
                    'text' => 'Data Paket UKK berhasil di Aktifkan!'
                ]);
            }
        } elseif($aksi == 'edit'){
            $this->emit('editModal');
        }
    }
    public function tambahModal(){
        $this->jml = 5;
        $this->emit('tambahModal');
    }
    public function store(){
        /*
        public $all_nomor_paket = [];
        public $nama_paket_id = [];
        public $nama_paket_en = [];
        public $status = [];
        */
        foreach($this->all_nomor_paket as $key => $nomor_paket){
            Paket_ukk::create([
                'paket_ukk_id'      => Str::uuid(),
                'sekolah_id'        => session('sekolah_id'),
                'jurusan_id'		=> $this->jurusan_id,
                'kurikulum_id'		=> $this->kurikulum_id,
                'kode_kompetensi'	=> $this->kurikulum_id,
                'nomor_paket'		=> $nomor_paket,
                'nama_paket_id'		=> $this->nama_paket_id[$key],
                'nama_paket_en'		=> $this->nama_paket_en[$key],
                'status'			=> $this->status[$key],
                'last_sync'			=> now(),
            ]);
        }
        $this->alert('success', 'Berhasil', [
            'text' => 'Data Paket UKK berhasil disimpan!'
        ]);
        $this->emit('close-modal');
    }
    public function store_unit(){
        foreach($this->kode_unit as $key => $kode_unit){
            Unit_ukk::create([
                'unit_ukk_id'   => Str::uuid(),
                'paket_ukk_id' 	=> $this->paket_ukk_id,
                'kode_unit'		=> $kode_unit,
                'nama_unit'		=> $this->nama_unit[$key],
                'last_sync'		=> now(),
            ]);
        }
        $this->alert('success', 'Berhasil', [
            'text' => 'Data Unit UKK berhasil disimpan!'
        ]);
        $this->emit('close-modal');
    }
    public function perbaharui(){
        $this->alert('success', 'Berhasil', [
            'text' => 'Data Paket UKK berhasil diperbaharui!'
        ]);
    }
    public function updatedJurusanId($value){
        $data_kurikulum = Kurikulum::where('jurusan_id', $value)->get();
        $this->dispatchBrowserEvent('data_kurikulum', ['data_kurikulum' => $data_kurikulum]);
    }
}
