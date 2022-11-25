<?php

namespace App\Http\Livewire\Penilaian;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Sikap as Sikap_model;
use App\Models\Nilai_sikap;

class Sikap extends Component
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
    public $nilai_sikap_id;
    public $data;
    public $sikap_id;
    public $opsi_sikap;
    public $uraian_sikap;

    public function getListeners()
    {
        return [
            'confirmed'
        ];
    }
    public function render()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Penilaian'], ['name' => "Sikap"]
        ];
        if(!status_penilaian()){
            return view('components.non-aktif', [
                'all_sikap' => Sikap_model::whereHas('sikap')->with('sikap')->orderBy('sikap_id')->get(),
                'breadcrumbs' => $breadcrumbs,
            ]);
        }
        $callback = function($query) {
			$query->whereHas('peserta_didik');
			$query->whereHas('rombongan_belajar');
			$query->with(['rombongan_belajar', 'peserta_didik']);
			$query->where('sekolah_id', session('sekolah_id'));
			$query->where('semester_id', session('semester_aktif'));
		};
        return view('livewire.penilaian.sikap', [
            'collection' => Nilai_sikap::whereHas('anggota_rombel', $callback)->with(['anggota_rombel' => $callback, 'ref_sikap'])
            ->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($query) {
                    $query->where('nama_penilaian', 'ILIKE', '%' . $this->search . '%')
                    //->orWhere('pembelajaran.nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                    ->orWhereIn('pembelajaran_id', function($query){
                        $query->select('pembelajaran_id')
                        ->from('pembelajaran')
                        ->where('sekolah_id', session('sekolah_id'))
                        ->where('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                    });
            })->paginate($this->per_page),
            'all_sikap' => Sikap_model::whereHas('sikap')->with('sikap')->orderBy('sikap_id')->get(),
            'breadcrumbs' => $breadcrumbs,
            'tombol_add' => [
                'wire' => NULL,
                'link' => '/penilaian/sikap/tambah',
                'color' => 'primary',
                'id' => '',
                'text' => 'Tambah Data'
            ]
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    public function getID($id){
        $this->nilai_sikap_id = $id;
        $this->data = Nilai_sikap::find($this->nilai_sikap_id);
        $this->sikap_id = $this->data->sikap_id;
        $this->opsi_sikap = $this->data->getRawOriginal('opsi_sikap');
        $this->uraian_sikap = $this->data->uraian_sikap;
        $this->emit('show-modal');
    }
    public function delete($id){
        $this->nilai_sikap_id = $id;
        $this->alert('question', 'Apakah Anda yakin?', [
            'text' => 'Tindak ini tidak dapat dikembalikan!',
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yakin',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Batal',
            'onDismissed' => 'cancelled',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
    }
    public function confirmed()
    {
        $a = Nilai_sikap::find($this->nilai_sikap_id);
        $a->delete();
        $this->alert('success', 'Nilai sikap berhasil dihapus');
    }
    public function update(){
        $this->data->sikap_id = $this->sikap_id;
        $this->data->opsi_sikap = $this->opsi_sikap;
        $this->data->uraian_sikap = $this->uraian_sikap;
        if($this->data->save()){
            $this->alert('success', 'Berhasil', [
                'text' => 'Nilai Sikap berhasil diperbaharui',
                'showConfirmButton' => true,
                'confirmButtonText' => 'Yakin',
                'onConfirmed' => 'salah',
                'allowOutsideClick' => false,
                'timer' => null,
                'toast' => false,
            ]);
        } else {
            $this->alert('error', 'Gagal', [
                'html' => 'Nilai Sikap gagal diperbaharui.<br>Silahkan coba beberapa saat lagi!',
                'showConfirmButton' => true,
                'confirmButtonText' => 'Yakin',
                'onConfirmed' => 'salah',
                'allowOutsideClick' => false,
                'timer' => null,
                'toast' => false,
            ]);
        }
        $this->emit('close-modal');
    }
}
