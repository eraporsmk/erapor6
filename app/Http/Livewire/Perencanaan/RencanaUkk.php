<?php

namespace App\Http\Livewire\Perencanaan;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Guru;
use App\Models\Paket_ukk;
use App\Models\Rencana_ukk;
use App\Models\Peserta_didik;
use App\Models\Nilai_ukk;

class RencanaUkk extends Component
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
    public $sortby = 'nama';
    public $sortbydesc = 'ASC';
    public $per_page = 10;

    public $semester_id;
    public $tingkat;
    public $data_rombongan_belajar = [];
    public $rombongan_belajar_id;
    public $penguji_internal;
    public $penguji_eksternal;
    public $data_internal = [];
    public $data_eksternal = [];
    public $paket_ukk = [];
    public $paket_kompetensi;
    public $jurusan_id;
    public $tanggal;
    public $show = FALSE;
    public $collection = [];
    public $rencana_ukk;
    public $siswa_dipilih = [];
    public $rencana_ukk_id;
    public function render()
    {
        $this->semester_id = session('semester_id');
        return view('livewire.perencanaan.rencana-ukk', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Perencanaan'], ['name' => "Uji Kompetensi Keahlian"]
            ]
        ]);
    }
    public function updatedTingkat($value)
    {
        $this->reset(['rombongan_belajar_id', 'data_rombongan_belajar', 'jurusan_id', 'collection', 'show']);
        if($value){
            $this->data_rombongan_belajar = Rombongan_belajar::select('rombongan_belajar_id', 'nama', 'jurusan_id')->where(function($query){
                $query->where('tingkat', $this->tingkat);
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('jenis_rombel', 1);
                //$query->whereHas('pembelajaran', $this->kondisi());
            })->get();
            $this->dispatchBrowserEvent('data_tingkat');
            $this->dispatchBrowserEvent('data_rombongan_belajar', ['data_rombongan_belajar' => $this->data_rombongan_belajar]);
        }
    }
    public function updatedRombonganBelajarId($value){
        $this->reset(['paket_ukk', 'paket_kompetensi', 'data_internal', 'data_eksternal', 'penguji_eksternal']);
        if($value){
            $rombongan_belajar = Rombongan_belajar::find($value);
            $this->jurusan_id = $rombongan_belajar->jurusan_id;
            $get_internal = jenis_gtk('guru');
            $get_eksternal = jenis_gtk('asesor');
            $callback = function($query){
                $query->whereRoleIs('internal', session('semester_id'));
            };
            //dd($get_internal);
            $this->data_internal = Guru::where('sekolah_id', session('sekolah_id'))->whereIn('jenis_ptk_id', $get_internal)->whereHas('pengguna', $callback)->with(['pengguna' => $callback])->get();
		    $this->data_eksternal = Guru::where('sekolah_id', session('sekolah_id'))->whereHas('dudi')->with('dudi')->whereIn('jenis_ptk_id', $get_eksternal)->get();
            $this->dispatchBrowserEvent('data_internal', ['data_internal' => $this->data_internal]);
            $this->dispatchBrowserEvent('data_eksternal', ['data_eksternal' => $this->data_eksternal]);
        }
    }
    //penguji_eksternal
    public function updatedPengujiEksternal($value){
        $this->reset(['tanggal', 'collection', 'show', 'paket_kompetensi']);
        if($value){
            $this->paket_ukk = Paket_ukk::where(function($query){
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('jurusan_id', $this->jurusan_id);
                $query->orWhereNull('sekolah_id');
                $query->where('jurusan_id', $this->jurusan_id);
            })->get();
            $this->dispatchBrowserEvent('paket_ukk', ['paket_ukk' => $this->paket_ukk]);
        }
    }
    public function updatedPaketKompetensi($value){
        $this->reset(['tanggal', 'collection', 'show']);
        if($value){
            $this->paket_ukk_id = $value;
            $this->rencana_ukk = Rencana_ukk::where('paket_ukk_id', $this->paket_ukk_id)->first();
            if($this->rencana_ukk){
                $this->rencana_ukk_id = $this->rencana_ukk->rencana_ukk_id;
                $this->tanggal = $this->rencana_ukk->tanggal_sertifikat;
            }
            $this->collection = Peserta_didik::with([
                'anggota_rombel' => function($query){
                    $query->with(['nilai_ukk_satuan' => function($query){
                        $query->where('rencana_ukk_id', $this->rencana_ukk_id);
                    }]);
                }
            ])->whereHas('anggota_rombel', function($query){
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
            })->orderBy('nama')->get();
            foreach($this->collection as $siswa){
                if($siswa->nilai_ukk && $this->rencana_ukk){
                    $this->siswa_dipilih[$siswa->anggota_rombel->anggota_rombel_id] = $siswa->peserta_didik_id;
                }
            }
            $this->show = TRUE;
        }
    }
    public function updatedSiswaDipilih(){
        $this->collection = Peserta_didik::with([
            'anggota_rombel' => function($query){
                $query->with(['nilai_ukk_satuan' => function($query){
                    $query->where('rencana_ukk_id', $this->rencana_ukk_id);
                }]);
            }
        ])->whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->orderBy('nama', 'ASC')->get();
        /*Peserta_didik::with(['nilai_ukk' => function($query){
            $query->where('rencana_ukk_id', $this->rencana_ukk_id);
        }])->whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->orderBy('nama')->get();*/
        
    }
    public function store(){
        $this->validate(
            [
                'tingkat' => 'required',
                'rombongan_belajar_id' => 'required',
                'penguji_internal' => 'required',
                'penguji_eksternal' => 'required',
                'paket_kompetensi' => 'required',
                'tanggal' => 'required',
            ],
            [
                'tingkat.required' => 'Tingkat Kelas tidak boleh kosong!',
                'rombongan_belajar_id.required' => 'Rombongan Belajar tidak boleh kosong!',
                'penguji_internal.required' => 'Penguji Internal tidak boleh kosong!',
                'penguji_eksternal.required' => 'Penguji Eksternal tidak boleh kosong!',
                'paket_kompetensi.required' => 'Paket Kompetensi Kelas tidak boleh kosong!',
                'tanggal.required' => 'Tanggal Sertifikat tidak boleh kosong!',
            ]
        );
        if($this->rencana_ukk){
            $rencana_ukk = $this->rencana_ukk;
            $rencana_ukk->paket_ukk_id = $this->paket_ukk_id;
            $rencana_ukk->internal = $this->penguji_internal;
            $rencana_ukk->eksternal = $this->penguji_eksternal;
            $rencana_ukk->tanggal_sertifikat = $this->tanggal;
            $rencana_ukk->save();
        } else {
            $rencana_ukk = Rencana_ukk::create([
                'semester_id'			=> session('semester_aktif'),
                'paket_ukk_id'			=> $this->paket_ukk_id,
                'internal'				=> $this->penguji_internal,
                'eksternal'				=> $this->penguji_eksternal,
                'sekolah_id' 			=> session('sekolah_id'),
                'tanggal_sertifikat'	=> $this->tanggal,
                'last_sync' 			=> now(), 
            ]);
        }
        $deleted = [];
        foreach($this->siswa_dipilih as $anggota_rombel_id => $peserta_didik_id){
            if($peserta_didik_id){
                Nilai_ukk::firstOrCreate(
                    [
                    'rencana_ukk_id'		=> $rencana_ukk->rencana_ukk_id,
                    'anggota_rombel_id'		=> $anggota_rombel_id,
                    'peserta_didik_id'		=> $peserta_didik_id,
                    ],
                    [
                    'sekolah_id' 			=> session('sekolah_id'),
                    'nilai'					=> 0,
                    'last_sync' 			=> now(), 
                    ]
                );
            } else {
                $deleted[] = $anggota_rombel_id;
            }
        }
        if($deleted){
            Nilai_ukk::where('rencana_ukk_id', $rencana_ukk->rencana_ukk_id)->whereIn('anggota_rombel_id', $deleted)->delete();
        }
        $this->flash('success', 'Rencana Penilaian UKK berhasil disimpan', [], '/perencanaan/penilaian-ukk');
    }
}
