<?php

namespace App\Http\Livewire\Referensi;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Tujuan_pembelajaran;
use App\Models\Pembelajaran;

class TujuanPembelajaran extends Component
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
    public $sortby = 'updated_at';
    public $sortbydesc = 'DESC';
    public $per_page = 10;

    public $tp_id;
    public $data;
    public $deskripsi;

    protected $listeners = [
        'confirmed'
    ];

    public function render()
    {
        return view('livewire.referensi.tujuan-pembelajaran', [
            'collection' => Tujuan_pembelajaran::with(['cp.mata_pelajaran'])->where($this->kondisi())->orderBy($this->sortby, $this->sortbydesc)
            ->orderBy('updated_at', $this->sortbydesc)
                ->when($this->search, function($query){
                    $this->kondisi();
                    /**/
                    //$query->orWhere('cp.elemen', 'ILIKE', '%' . $this->search . '%');
                    //$query->orWhere('cp.mata_pelajaran.nama', 'ILIKE', '%' . $this->search . '%');
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Tujuan Pembelajaran"]
            ],
            'tombol_add' => [
                'wire' => '',
                'link' => '/referensi/tujuan-pembelajaran/tambah',
                'color' => 'primary',
                'text' => 'Tambah Data'
            ]
        ]);
    }
    private function kondisi(){
        $callback = function($query){
            if($this->search){
                $query->where('deskripsi', 'ILIKE', '%' . $this->search . '%');                
            }
            $query->whereHas('cp', function($query){
                $query->where('aktif', 1);
                $query->whereHas('pembelajaran', function($query){
                    $query->where('guru_id', session('guru_id'));
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    if($this->search){
                        $query->where('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');                
                    }
                    $query->orWhere('guru_pengajar_id', session('guru_id'));
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    if($this->search){
                        $query->where('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');                
                    }
                });
            });
            $query->orWhereHas('kd', function($query){
                $query->where('aktif', 1);
                $query->whereHas('pembelajaran', function($query){
                    $query->where('guru_id', session('guru_id'));
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    if($this->search){
                        $query->where('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');                
                    }
                    $query->orWhere('guru_pengajar_id', session('guru_id'));
                    $query->whereNotNull('kelompok_id');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    if($this->search){
                        $query->where('nama_mata_pelajaran', 'ILIKE', '%' . $this->search . '%');                
                    }
                });
            });
        };
        return $callback;
    }
    public function getId($tp_id, $aksi){
        $this->tp_id = $tp_id;
        $this->data = Tujuan_pembelajaran::find($this->tp_id);
        $this->deskripsi = $this->data->deskripsi;
        if($aksi == 'edit'){
            $this->emit('editTP');
        } else {
            $this->alert('question', 'Apakah Anda yakin?', [
                'text' => 'Tindakan ini tidak dapat dikembalikan!',
                'showConfirmButton' => true,
                'confirmButtonText' => 'Hapus',
                'showCancelButton' => true,
                'cancelButtonText' => 'Batal',
                'onConfirmed' => 'confirmed',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        }
    }
    public function confirmed(){
        if($this->data->delete()){
            $this->alert('success', 'Data TP berhasil di hapus!', [
                'toast' => false
            ]);
        } else {
            $this->alert('success', 'Data TP berhasil di hapus!', [
                'toast' => false
            ]);
        }
    }
    public function perbaharui(){
        $this->data->deskripsi = $this->deskripsi;
        if($this->data->save()){
            $this->alert('success', 'Berhasil', [
                'text' => 'Data TP berhasil di perbaharui',
                'toast' => false
            ]);
        } else {
            $this->alert('error', 'Gagal', [
                'text' => 'Data TP gagal di perbaharui. Silahkan coba beberapa saat lagi!',
                'toast' => false
            ]);
        }
        $this->resetPage();
        $this->emit('close-modal');
    }
}
