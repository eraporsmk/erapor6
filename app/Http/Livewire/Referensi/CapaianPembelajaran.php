<?php

namespace App\Http\Livewire\Referensi;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Capaian_pembelajaran;
use App\Models\Pembelajaran;

class CapaianPembelajaran extends Component
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
    public $sortby = 'mata_pelajaran_id';
    public $sortbydesc = 'ASC';
    public $per_page = 10;

    public $cp_id;
    public $data;

    public function render()
    {
        return view('livewire.referensi.capaian-pembelajaran', [
            'collection' => Capaian_pembelajaran::with(['mata_pelajaran'])->withCount('tp')->where(function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('guru_id', session('guru_id'));
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->whereNull('induk_pembelajaran_id');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->orWhere('guru_pengajar_id', session('guru_id'));
                    $query->whereNotNull('kelompok_id');
                    $query->whereNotNull('no_urut');
                    $query->whereNull('induk_pembelajaran_id');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                });
            })->orderBy($this->sortby, $this->sortbydesc)
            ->orderBy('updated_at', $this->sortbydesc)
                ->when($this->search, function($query) {
                    $query->where('elemen', 'ILIKE', '%' . $this->search . '%');
                    $query->whereHas('mata_pelajaran', function($query){
                        $query->where('nama', 'ILIKE', '%' . $this->search . '%');
                    });
                    $query->whereHas('pembelajaran', function($query){
                        $query->where('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                        $query->where('guru_id', session('guru_id'));
                        $query->whereNotNull('kelompok_id');
                        $query->whereNotNull('no_urut');
                        $query->whereNull('induk_pembelajaran_id');
                        $query->where('sekolah_id', session('sekolah_id'));
                        $query->where('semester_id', session('semester_aktif'));
                        $query->orWhere('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                        $query->where('guru_pengajar_id', session('guru_id'));
                        $query->whereNotNull('kelompok_id');
                        $query->whereNotNull('no_urut');
                        $query->whereNull('induk_pembelajaran_id');
                        $query->where('sekolah_id', session('sekolah_id'));
                        $query->where('semester_id', session('semester_aktif'));
                    });
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Capaian Pembelajaran"]
            ],
            'tombol_add' => [
                'wire' => '',
                'link' => '/referensi/capaian-pembelajaran/tambah',
                'color' => 'primary',
                'text' => 'Tambah Data'
            ]
        ]);
    }
    private function loggedUser(){
        return auth()->user();
    }
    public function getId($cp_id, $aksi){
        $this->cp_id = $cp_id;
        $this->data = Capaian_pembelajaran::find($this->cp_id);
        $data = $this->data;
        $data->aktif = ($aksi) ? 1 : 0;
        $data->save();
        if($aksi){
            $this->alert('success', 'Data CP berhasil di aktifkan!', [
                'toast' => false
            ]);
        } else {
            $this->alert('success', 'Data CP berhasil di nonaktifkan!', [
                'toast' => false
            ]);
        }
    }
    public function perbaharui(){
        $this->emit('close-modal');
    }
}
