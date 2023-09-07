<?php

namespace App\Http\Livewire\Referensi;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Kompetensi_dasar;
use App\Models\pembelajaran;
use App\Models\Mata_pelajaran;

class KompetensiDasar extends Component
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
    
    public $kompetensi_dasar_id;
    public $data;
    public $kd_lama;
    public $kd_baru;

    public function getListeners()
    {
        return [
            'hapus',
            'aktifkan',
            'nonaktif',
            'delete'
        ];
    }

    public function render()
    {
        return view('livewire.referensi.kompetensi-dasar', [
            'collection' => Kompetensi_dasar::has('mata_pelajaran')->with(['mata_pelajaran'])->where($this->kondisiKd())->orderBy($this->sortby, $this->sortbydesc)
                ->orderBy('status', 'DESC')
                ->when($this->search, function($query) {
                    //$query->where('kompetensi_dasar', 'ILIKE', '%' . $this->search . '%');
                    //$query->orWhere('mata_pelajaran.nama', 'ILIKE', '%' . $this->search . '%');
                    $query->where('id_kompetensi', 'ilike', '%'.$this->search.'%');
                    $query->where($this->kondisiKd());
					$query->orWhere('kompetensi_dasar', 'ilike', '%'.$this->search.'%');
                    $query->where($this->kondisiKd());
					$query->orWhere('kurikulum', 'ilike', '%'.$this->search.'%');
                    $query->where($this->kondisiKd());
					$query->orWhereHas('mata_pelajaran', function($q){ 
						$q->where('mata_pelajaran_id', 'ilike', '%'.$this->search.'%');
                        $q->orWhere('nama', 'ilike', '%'.$this->search.'%');
					});
                    $query->where($this->kondisiKd());
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
    private function kondisiKd(){
        return function($query){
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
        };
    }
    private function loggedUser(){
        return auth()->user();
    }
    public function getID($kompetensi_dasar_id, $aksi){
        $this->reset(['kd_lama', 'kd_baru']);
        $this->kompetensi_dasar_id = $kompetensi_dasar_id;
        $this->data = Kompetensi_dasar::find($this->kompetensi_dasar_id);
        if($aksi == 'edit'){
            $this->kd_lama = $this->data->kompetensi_dasar;
            $this->kd_baru = $this->data->kompetensi_dasar_alias;
            $this->emit('show-modal');
        } elseif($aksi == 'delete'){
            $this->alert('question', 'Apakah Anda yakin?', [
                'text' => 'Tindakan ini akan mengembalikan isi ringkasan ke bawaan aplikasi!',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'delete',
                'showCancelButton' => true,
                'cancelButtonText' => 'Batal',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        } elseif($aksi == 'nonaktif'){
            $this->alert('question', 'Apakah Anda yakin?', [
                'text' => 'Tindakan ini akan menonaktifkan data Kompetensi Dasar!',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'nonaktif',
                'showCancelButton' => true,
                'cancelButtonText' => 'Batal',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        } elseif($aksi == 'aktifkan'){
            $this->alert('question', 'Apakah Anda yakin?', [
                'text' => 'Tindakan ini akan mengaktifkan data Kompetensi Dasar!',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'aktifkan',
                'showCancelButton' => true,
                'cancelButtonText' => 'Batal',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        } else {
            $this->alert('question', 'Apakah Anda yakin?', [
                'text' => 'Tindakan ini akan menghapus data ganda Kompetensi Dasar!',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'hapus',
                'showCancelButton' => true,
                'cancelButtonText' => 'Batal',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        }
    }
    public function hapus(){
        $data = $this->data;
        $mapel = Mata_pelajaran::has('kompetensi_dasar')->with(['kompetensi_dasar'])->find($data->mata_pelajaran_id);
        foreach($mapel->kompetensi_dasar as $kd){
            $kompetensi_dasar_id[str_replace('.','',$kd->id_kompetensi)] = $kd->kompetensi_dasar_id;
        }
        $a = Kompetensi_dasar::where('mata_pelajaran_id', $data->mata_pelajaran_id)->whereNotIn('kompetensi_dasar_id', $kompetensi_dasar_id)->update(['aktif' => 0]);
        if($a){
            $this->show_alert('success', 'KD Mapel '.$mapel->nama.' dinonaktifkan sebanyak ('.$a.')');
        } else {
            $this->show_alert('error', 'Mapel '.$mapel->nama.' tidak memiliki KD Ganda');
        }
        $this->resetPage();
    }
    public function aktifkan(){
        $data = $this->data;
        $data->aktif = 1;
        if($data->save()){
            $this->show_alert('success', 'Data KD berhasil di aktifkan');
        } else {
            $this->show_alert('error', 'Data KD gagal di aktifkan. Silahkan coba beberapa saat lagi!');
        }
    }
    public function nonaktif(){
        $data = $this->data;
        $data->aktif = 0;
        if($data->save()){
            $this->show_alert('success', 'Data KD berhasil di nonaktifkan');
        } else {
            $this->show_alert('error', 'Data KD gagal di nonaktifkan. Silahkan coba beberapa saat lagi!');
        }
    }
    public function delete(){
        $data = $this->data;
        $data->kompetensi_dasar_alias = NULL;
        if($data->save()){
            $this->show_alert('success', 'Data ringkasan KD berhasil dikembalikan ke bawaan aplikasi');
        } else {
            $this->show_alert('error', 'Data ringkasan KD gagal dikembalikan ke bawaan aplikasi. Silahkan coba beberapa saat lagi!');
        }
    }
    public function store(){
        $data = $this->data;
        $data->kompetensi_dasar_alias = $this->kd_baru;
        if($data->save()){
            $this->show_alert('success', 'Data ringkasan KD berhasil diperbaharui');
        } else {
            $this->show_alert('error', 'Data ringkasan KD gagal diperbaharui. Silahkan coba beberapa saat lagi!');
        }
        $this->emit('close-modal');
    }
    public function show_alert($type, $text){
        $this->alert($type, $text, [
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
        $this->resetPage();
    }
}
