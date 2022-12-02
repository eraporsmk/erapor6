<?php

namespace App\Http\Livewire\Referensi;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Peserta_didik;

class PasswordPesertaDidik extends Component
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
        $user = auth()->user();
        $rombongan_belajar_id = $user->guru->rombongan_belajar->rombongan_belajar_id;
        return view('livewire.referensi.password-peserta-didik', [
            'collection' => Peserta_didik::whereHas('anggota_rombel', function($query) use ($rombongan_belajar_id){
                $query->where('semester_id', session('semester_aktif'));
                $query->where('rombongan_belajar_id', $rombongan_belajar_id);
            })->with(['user'])->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($ptk) {
                    $ptk->where('nama', 'ILIKE', '%' . $this->search . '%')
                    ->orWhere('nisn', 'ILIKE', '%' . $this->search . '%');
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Data Password Peserta Didik"]
            ],
        ]);
    }
}
