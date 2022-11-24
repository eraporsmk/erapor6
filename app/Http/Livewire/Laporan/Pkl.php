<?php

namespace App\Http\Livewire\Laporan;

use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Dudi;
use App\Models\Prakerin;

class Pkl extends Component
{
    use LivewireAlert;
    public $allowed;
    public $show = FALSE;
    public $form = FALSE;
    public $tingkat;
    public $nama_kurikulum;
    public $data_siswa = [];
    public $data_dudi = [];
    public $mitra_prakerin = [];
    public $lokasi_prakerin = [];
    public $skala = [];
    public $lama_prakerin = [];
    public $keterangan_prakerin = [];
    public $dudi_id;
    public $dudi;

    public function getListeners()
    {
        return [
            'confirmed' => '$refresh',
            'showAlert',
        ];
    }
    public function render()
    {
        return view('livewire.laporan.pkl', [
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Laporan'], ['name' => "Praktik Kerja Lapangan"]
            ]
        ]);
    }
    public function loggedUser(){
        return auth()->user();
    }
    private function check_walas(){
        if($this->loggedUser()->hasRole('wali', session('semester_id'))){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function mount(){
        if(!$this->check_walas()){
            $rombel = Rombongan_belajar::with(['kurikulum'])->where(function($query){
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('semester_id', session('semester_aktif'));
                $query->where('guru_id', $this->loggedUser()->guru_id);
            })->first();
            if(Str::contains($rombel->kurikulum->nama_kurikulum, '2013')){
                $tingkat_allowed = 11;
            } elseif(Str::contains($rombel->kurikulum->nama_kurikulum, 'Merdeka')){
                $tingkat_allowed = 12;
            }
            $this->tingkat = $tingkat_allowed;
            if($rombel->tingkat == $tingkat_allowed){
                $this->allowed = TRUE;
                $this->data_dudi = Dudi::where('sekolah_id', session('sekolah_id'))->whereHas('akt_pd', function($query){
                    $query->whereHas('anggota_akt_pd', function($query){
                        $query->whereHas('siswa', function($query){
                            $query->whereHas('anggota_rombel', $this->callback_anggota_rombel());
                        });
                    });
                })->orderBy('nama')->get();
            }
            $this->nama_kurikulum = $rombel->kurikulum->nama_kurikulum;
        }
    }
    private function callback_anggota_rombel(){
        return function($query){
            $query->whereHas('rombongan_belajar', function($query){
                $query->where('semester_id', session('semester_aktif'));
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('guru_id', $this->loggedUser()->guru_id);
            });
            $query->with(['single_prakerin']);
        };
    }
    public function updatedDudiId($value){
        $this->reset(['data_siswa', 'show', 'lokasi_prakerin', 'lama_prakerin', 'skala', 'keterangan_prakerin']);
        $this->dudi_id = $value;
        $this->dudi = Dudi::find($this->dudi_id);
        $callback_anggota_rombel = $this->callback_anggota_rombel();
        $callback_anggota_akt_pd = function($query){
            $query->whereHas('akt_pd', function($query){
                $query->whereHas('dudi', function($query){
                    $query->where('dudi.dudi_id', $this->dudi_id);
                });
            });
        };
        $this->data_siswa = Peserta_didik::whereHas('anggota_akt_pd', $callback_anggota_akt_pd)->whereHas('anggota_rombel', $callback_anggota_rombel)->with([
            'anggota_akt_pd' => $callback_anggota_akt_pd,
            'anggota_rombel' => $callback_anggota_rombel,
        ])->orderBy('nama')->get();
        if($this->data_siswa->count()){
            foreach($this->data_siswa as $siswa){
                $this->lokasi_prakerin[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_prakerin) ? $siswa->anggota_rombel->single_prakerin->lokasi_prakerin : $this->dudi->alamat_jalan;
                $this->skala[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_prakerin) ? $siswa->anggota_rombel->single_prakerin->skala : '';
                $this->lama_prakerin[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_prakerin) ? $siswa->anggota_rombel->single_prakerin->lama_prakerin : '';
                $this->keterangan_prakerin[$siswa->anggota_rombel->anggota_rombel_id] = ($siswa->anggota_rombel->single_prakerin) ? $siswa->anggota_rombel->single_prakerin->keterangan_prakerin : '';
            }
            $this->show = TRUE;
        }
    }
    public function updatedMitraPrakerin($value){
        $value = explode('|', $value);
        $this->lokasi_prakerin[$value[0]] = $value[2];
    }
    public function store(){
        foreach($this->lokasi_prakerin as $anggota_rombel_id => $lokasi_prakerin){
            if($this->lama_prakerin[$anggota_rombel_id] && $this->skala[$anggota_rombel_id] && $this->keterangan_prakerin[$anggota_rombel_id]){
                Prakerin::UpdateOrCreate(
                    [
                        'anggota_rombel_id' => $anggota_rombel_id
                    ],
                    [
                        'sekolah_id' => session('sekolah_id'),
                        'mitra_prakerin' 	=> $this->dudi->nama,
                        'lokasi_prakerin'		=> $lokasi_prakerin,
                        'lama_prakerin'		=> $this->lama_prakerin[$anggota_rombel_id],
                        'skala'		=> $this->skala[$anggota_rombel_id],
                        'keterangan_prakerin'		=> $this->keterangan_prakerin[$anggota_rombel_id],
                        'last_sync'	=> now(),
                    ]
                );
            }
        }
        $this->emit('showAlert');
    }
    public function showAlert(){
        $this->reset(['dudi_id', 'dudi', 'data_siswa', 'show', 'lokasi_prakerin', 'lama_prakerin', 'skala', 'keterangan_prakerin']);
        $this->alert('success', 'Data Praktik Kerja Lapangan berhasil disimpan');
        $this->emit('confirmed');
    }
}
