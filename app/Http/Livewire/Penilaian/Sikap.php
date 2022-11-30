<?php

namespace App\Http\Livewire\Penilaian;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Budaya_kerja;
use App\Models\Elemen_budaya_kerja;
use App\Models\Nilai_budaya_kerja;

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
    public $nilai_budaya_kerja_id;
    public $data;
    public $budaya_kerja_id;
    public $elemen_id;
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
                //'all_sikap' => Sikap_model::whereHas('sikap')->with('sikap')->orderBy('sikap_id')->get(),
                'all_sikap' => [],
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
            'collection' => Nilai_budaya_kerja::whereHas('anggota_rombel', $callback)->where('guru_id', session('guru_id'))->with(['anggota_rombel' => $callback, 'budaya_kerja', 'elemen_budaya_kerja'])
            ->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($query) {
                    $query->where('deskripsi', 'ILIKE', '%' . $this->search . '%');
                    //->orWhere('pembelajaran.nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                    /*$query->orWhereIn('peserta_didik_id', function($query){
                        $query->select('peserta_didik_id')
                        ->from('pembelajaran')
                        ->where('sekolah_id', session('sekolah_id'))
                        ->where('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');
                    });*/
            })->paginate($this->per_page),
            'all_sikap' => Budaya_kerja::with(['elemen_budaya_kerja'])->get(),
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
        $this->nilai_budaya_kerja_id = $id;
        $this->data = Nilai_budaya_kerja::with(['budaya_kerja'])->find($this->nilai_budaya_kerja_id);
        $this->budaya_kerja_id = $this->data->budaya_kerja_id;
        $this->elemen_id = $this->data->elemen_id;
        $this->opsi_sikap = $this->data->opsi_id;
        $this->uraian_sikap = $this->data->deskripsi;
        $this->dispatchBrowserEvent('data', [
            'data' => $this->data,
            'elemen_budaya_kerja' => Elemen_budaya_kerja::where('budaya_kerja_id', $this->budaya_kerja_id)->get()->unique('elemen'),
        ]);
        $this->emit('show-modal');
    }
    public function delete($id){
        $this->nilai_budaya_kerja_id = $id;
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
        $a = Nilai_sikap::find($this->nilai_budaya_kerja_id);
        $a->delete();
        $this->alert('success', 'Nilai sikap berhasil dihapus');
    }
    public function update(){
        $this->data->budaya_kerja_id = $this->budaya_kerja_id;
        $this->data->elemen_id = $this->elemen_id;
        $this->data->opsi_id = $this->opsi_sikap;
        $this->data->deskripsi = $this->uraian_sikap;
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
