<?php

namespace App\Http\Livewire\Referensi;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Peserta_didik;

class PesertaDidikKeluar extends Component
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
    public $sortby = 'nama';
    public $sortbydesc = 'ASC';
    public $per_page = 10;

    public function render()
    {
        return view('livewire.referensi.peserta-didik-keluar', [
            'collection' => Peserta_didik::whereHas('pd_keluar', function($query){
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('semester_id', session('semester_aktif'));
            })->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($ptk) {
                    $ptk->where('nama', 'ILIKE', '%' . $this->search . '%')
                    ->orWhere('nisn', 'ILIKE', '%' . $this->search . '%');
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Data Peserta Didik Keluar"]
            ]
        ]);
    }
}
