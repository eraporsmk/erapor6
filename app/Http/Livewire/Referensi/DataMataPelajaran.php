<?php

namespace App\Http\Livewire\Referensi;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Mata_pelajaran;

class DataMataPelajaran extends Component
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
    public $sortby = 'created_at';
    public $sortbydesc = 'DESC';
    public $per_page = 10;

    public function render()
    {
        return view('livewire.referensi.data-mata-pelajaran', [
            'data_mata_pelajaran' => Mata_pelajaran::whereHas('pembelajaran', function($query){
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('semester_id', session('semester_aktif'));
            })->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($mata_pelajaran) {
                    $mata_pelajaran->where('nama', 'ILIKE', '%' . $this->search . '%')
                    ->orWhere('mata_pelajaran_id', 'ILIKE', '%' . $this->search . '%');
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Data Mata Pelajaran"]
            ]
        ]);
    }
}
