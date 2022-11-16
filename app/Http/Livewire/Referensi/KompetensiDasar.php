<?php

namespace App\Http\Livewire\Referensi;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Kompetensi_dasar;
use App\Models\pembelajaran;
class KompetensiDasar extends Component
{
    use WithPagination;
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

    public function render()
    {
        return view('livewire.referensi.kompetensi-dasar', [
            'collection' => Kompetensi_dasar::with(['mata_pelajaran'])->where(function($query){
                $query->whereHas('pembelajaran', function($query){
                    $query->where('guru_id', $this->loggedUser()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->orWhere('guru_pengajar_id', $this->loggedUser()->guru_id);
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                });
                $query->whereNotIn('kurikulum', [2006, 2013, 2022]);
            })->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($query) {
                    $query->where('kompetensi_dasar', 'ILIKE', '%' . $this->search . '%');
                    $query->orWhere('mata_pelajaran.nama', 'ILIKE', '%' . $this->search . '%');
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Data Kompetensi Dasar"]
            ], 
            'tombol_add' => [
                'wire' => '',
                'link' => '/referensi/kompetensi-dasar/tambah',
                'color' => 'primary',
                'text' => 'Tambah Data'
            ]
        ]);
    }
    private function loggedUser(){
        return auth()->user();
    }
}
