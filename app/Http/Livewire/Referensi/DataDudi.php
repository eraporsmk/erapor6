<?php

namespace App\Http\Livewire\Referensi;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Dudi;
use App\Models\Akt_pd;
use App\Models\Peserta_didik;
use App\Models\Anggota_akt_pd;

class DataDudi extends Component
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
    public $dudi_id;
    public $nama;
    public $dudi;
    public $nama_prakerin;
    public $anggota_akt_pd = [];
    public $akt_pd_id;
    public $anggota_akt_pd_id;
    protected $listeners = ['confirmed_delete'];

    public function render()
    {
        return view('livewire.referensi.data-dudi', [
            'collection' => Dudi::where(function($query){
                $query->where('sekolah_id', session('sekolah_id'));
            })->withCount(['akt_pd'])->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($ptk) {
                    $ptk->where('nama', 'ILIKE', '%' . $this->search . '%')
                    ->orWhere('nama_bidang_usaha', 'ILIKE', '%' . $this->search . '%');
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Data DUDI"]
            ]
        ]);
    }
    public function getID($dudi_id){
        $this->dudi_id = $dudi_id;
        $this->getDudi();
    }
    public function aktPdID($akt_pd_id){
        $this->akt_pd_id = $akt_pd_id;
        $this->nama_prakerin = Akt_pd::select('akt_pd_id', 'judul_akt_pd')->find($akt_pd_id)->judul_akt_pd;
        $this->getAnggotaAktPd();
    }
    public function getAnggotaAktPd(){
        $this->anggota_akt_pd = Peserta_didik::whereHas('anggota_akt_pd', function($query){
            $query->where('akt_pd_id', $this->akt_pd_id);
        })->with(['anggota_akt_pd' => function($query){
            $query->where('akt_pd_id', $this->akt_pd_id);
        }])->get();
    }
    public function confirmed_delete(){
        $a = Anggota_akt_pd::find($this->anggota_akt_pd_id);
        $a->delete();
        $this->alert('success', 'Anggota Aktifitas '.$this->nama_prakerin.' berhasil dikeluarkan', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
        $this->getAnggotaAktPd();
    }
    public function keluarkanAnggota($anggota_akt_pd_id){
        $this->anggota_akt_pd_id = $anggota_akt_pd_id;
        $this->alert('question', 'Apakah Anda yakin?', [
            'text' => 'Tindakan ini tidak dapat dikembalikan!',
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yakin',
            'onConfirmed' => 'confirmed_delete',
            'showCancelButton' => true,
            'cancelButtonText' => 'Batal',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
    }
    public function getDudi(){
        $this->dudi = Dudi::with(['mou' => function($query){
            $query->with(['akt_pd' => function($query){
                $query->with([
                    'bimbing_pd' => function($query){
                        $query->with(['guru' => function($query){
                            $query->select('guru_id', 'nama');
                        }]);
                        $query->orderBy('urutan_pembimbing');
                    }
                ]);
                $query->withCount(['anggota_akt_pd']);
            }]);
        }])->find($this->dudi_id);
        $this->nama = $this->dudi->nama;
    }
}
