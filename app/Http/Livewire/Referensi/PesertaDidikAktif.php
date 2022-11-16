<?php

namespace App\Http\Livewire\Referensi;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Peserta_didik;

class PesertaDidikAktif extends Component
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
    public $rombongan_belajar_id;

    public function render()
    {
        $user = auth()->user();
        if($user->hasRole('wali', session('semester_id'))){
            $this->rombongan_belajar_id = $user->guru->rombongan_belajar->rombongan_belajar_id;
        } else {
            $this->rombongan_belajar_id = NULL;
        }
        return view('livewire.referensi.peserta-didik-aktif', [
            'collection' => Peserta_didik::whereHas('anggota_rombel', $this->kondisi())
            ->with(['anggota_rombel' => $this->kondisi()])
            ->orderBy($this->sortby, $this->sortbydesc)
            ->when($this->search, function($query) {
                $query->where('nama', 'ILIKE', '%' . $this->search . '%');
                $query->whereHas('anggota_rombel', $this->kondisi());
                $query->orWhere('nisn', 'ILIKE', '%' . $this->search . '%');
                $query->whereHas('anggota_rombel', $this->kondisi());
                $query->orWhereHas('agama', function($query){
                    $query->where('nama', 'ILIKE', '%' . $this->search . '%');
                });
                $query->whereHas('anggota_rombel', $this->kondisi());
                $query->orWhere('tempat_lahir', 'ILIKE', '%' . $this->search . '%');
                $query->whereHas('anggota_rombel', $this->kondisi());
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Data Peserta Didik Aktif"]
            ]
        ]);
    }
    public function kondisi(){
        return function($query){
            $query->where('semester_id', session('semester_aktif'));
            if($this->rombongan_belajar_id){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            } else {
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 1);
                });
            }
            $query->with(['rombongan_belajar']);
        };
    }
}
