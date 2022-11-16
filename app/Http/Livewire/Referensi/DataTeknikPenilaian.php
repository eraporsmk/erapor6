<?php

namespace App\Http\Livewire\Referensi;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Teknik_penilaian;
use Helper;

class DataTeknikPenilaian extends Component
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
    public $sortby = 'kompetensi_id';
    public $sortbydesc = 'ASC';
    public $per_page = 10;
    public function render()
    {
        return view('livewire.referensi.data-teknik-penilaian', [
            'collection' => Teknik_penilaian::where(function($query){
                $query->where('sekolah_id', session('sekolah_id'));
                $query->orWhereNull('sekolah_id');
            })->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($query) {
                    $query->where('nama', 'ILIKE', '%' . $this->search . '%');
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Data Teknik Penilaian"]
            ]
        ]);
    }
}
