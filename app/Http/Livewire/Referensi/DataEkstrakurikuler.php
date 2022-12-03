<?php

namespace App\Http\Livewire\Referensi;

use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Ekstrakurikuler;
use App\Models\Peserta_didik;
use App\Models\Rombongan_belajar;
use App\Models\Anggota_rombel;
use App\Models\Semester;
use Artisan;

class DataEkstrakurikuler extends Component
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
    public $nama_kelas;
    public $anggota_rombel = [];
    public $rombongan_belajar_id;

    public function getListeners()
    {
        return [
            'confirmed',
            'sync_anggota',
        ];
    }
    public function render()
    {
        return view('livewire.referensi.data-ekstrakurikuler', [
            'collection' => Ekstrakurikuler::where(function($query){
                $query->has('rombongan_belajar');
                $query->where('sekolah_id', session('sekolah_id'));
                $query->where('semester_id', session('semester_aktif'));
            })->with([
                'guru' => function($query){
                    $query->select('guru_id', 'nama');
                },
                'rombongan_belajar' => function($query){
                    $query->select('rombongan_belajar_id');
                    $query->withCount('anggota_rombel');
                }
            ])->orderBy($this->sortby, $this->sortbydesc)
                ->when($this->search, function($query) {
                    $query->where('nama_ekskul', 'ILIKE', '%' . $this->search . '%');
                    $query->has('rombongan_belajar');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->orWhere('nama_ketua', 'ILIKE', '%' . $this->search . '%');
                    $query->has('rombongan_belajar');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
                    $query->orWhereIn('guru_id', function($query){
                        $query->select('guru_id')->from('guru')->where('nama', 'ILIKE', '%' . $this->search . '%');
                    });
                    $query->has('rombongan_belajar');
                    $query->where('sekolah_id', session('sekolah_id'));
                    $query->where('semester_id', session('semester_aktif'));
            })->paginate($this->per_page),
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Referensi'], ['name' => "Data Ekstrakurikuler"]
            ]
        ]);
    }
    public function viewAnggota($rombongan_belajar_id){
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        $this->anggota_rombel = Peserta_didik::with(['anggota_rombel' => function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        }])->whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->orderBy('nama')->get();
        $this->getRombel();
    }
    public function syncAnggota($rombongan_belajar_id){
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        $this->proses_sync();
    }
    public function sync_anggota(){
        $this->resetPage();
    }
    public function getRombel(){
        $find = Rombongan_belajar::find($this->rombongan_belajar_id);
        $this->nama_kelas = $find->nama;
    }
    public function keluarkanAnggota($anggota_rombel_id, $rombongan_belajar_id){
        $this->rombongan_belajar_id = $rombongan_belajar_id;
        $a = Anggota_rombel::find($anggota_rombel_id);
        $a->delete();
        $this->alert('success', 'Anggota Ekstrakurikuler berhasil dikeluarkan', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed',
            'allowOutsideClick' => false,
            'timer' => null
        ]);
    }
    public function getAnggota(){
        $this->anggota_rombel = Peserta_didik::with(['anggota_rombel' => function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        }])->whereHas('anggota_rombel', function($query){
            $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
        })->get();
        $this->getRombel();
    }
    public function confirmed(){
        $this->getAnggota();
    }
    public function proses_sync(){
        $semester = Semester::find(session('semester_aktif'));
        $user = auth()->user();
        $data_sync = [
            'username_dapo'		=> $user->email,
            'password_dapo'		=> $user->password,
            'npsn'				=> $user->sekolah->npsn,
            'tahun_ajaran_id'	=> $semester->tahun_ajaran_id,
            'semester_id'		=> $semester->semester_id,
            'sekolah_id'		=> $user->sekolah->sekolah_id,
            'satuan'			=> $this->rombongan_belajar_id,
        ];
        $response = Http::post('http://app.erapor-smk.net/api/dapodik/anggota_ekskul_by_rombel', $data_sync);
        $return = $response->object();
        if($return){
            $this->simpan_anggota_ekskul($return->dapodik, $user, $semester);
            $this->alert('success', 'Anggota Ekstrakurikuler berhasil disinkronisasi', [
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'sync_anggota',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        } else {
            $this->alert('error', 'Server tidak merespon', [
                'text' => 'Anggota Ekstrakurikuler gagal disinkronisasi',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'onConfirmed' => 'sync_anggota',
                'allowOutsideClick' => false,
                'timer' => null
            ]);
        }
    }
    private function simpan_anggota_ekskul($data, $user, $semester){
        $anggota_rombel_id = [];
        foreach($data as $d){
            $anggota_rombel_id[] = $d->anggota_rombel_id;
            $pd = Peserta_didik::find($d->peserta_didik_id);
            if($pd){
                $this->simpan_anggota_rombel($d, $user, $semester, NULL);
            }
        }
        if($anggota_rombel_id){
            Anggota_rombel::where(function($query) use ($anggota_rombel_id, $user, $semester){
                /*$query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 51);
                });*/
                $query->where('rombongan_belajar_id', $this->rombongan_belajar_id);
                $query->where('sekolah_id', $user->sekolah_id);
                $query->where('semester_id', $semester->semester_id);
                $query->whereNotIn('anggota_rombel_id', $anggota_rombel_id);
            })->delete();
        }
    }
    private function simpan_anggota_rombel($data, $user, $semester, $deleted_at){
        Anggota_rombel::withTrashed()->updateOrCreate(
            [
                'anggota_rombel_id' => $data->anggota_rombel_id,
            ],
            [
                'sekolah_id' => $user->sekolah_id,
                'semester_id' => $semester->semester_id,
                'rombongan_belajar_id' => $data->rombongan_belajar_id,
                'peserta_didik_id' => $data->peserta_didik_id,
                'anggota_rombel_id_dapodik' => $data->anggota_rombel_id,
                'deleted_at' => $deleted_at,
                'last_sync' => now(),
            ]
        );
    }
}
