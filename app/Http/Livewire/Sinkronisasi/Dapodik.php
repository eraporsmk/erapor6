<?php

namespace App\Http\Livewire\Sinkronisasi;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Kompetensi_dasar;
use App\Models\Capaian_pembelajaran;
use App\Models\Mst_wilayah;
use App\Models\Jurusan;
use App\Models\Kurikulum;
use App\Models\Mata_pelajaran;
use App\Models\Mata_pelajaran_kurikulum;
use Carbon\Carbon;
use Storage;
use Artisan;
use DB;
class Dapodik extends Component
{
    use LivewireAlert;
    public $online = FALSE;
    public $showSyncButton = TRUE;
    public $syncText = 'Menyiapkan data sinkronisasi';
    public $server = 'dapodik';
    public $satuan = 'all';
    public $prosesSync = FALSE;
    public $sekolah_id;
    public $respon_artisan;

    public function getListeners()
    {
        return [
            'confirmed',
            'prosesSync',
            'delaySync',
            'finishSync',
        ];
    }
    public function data_dapodik(){
        try {
            $semester = Semester::find(session('semester_aktif'));
            $user = auth()->user();
            $data_sync = [
                'username_dapo'		=> $user->email,
                'password_dapo'		=> $user->password,
                'npsn'				=> $user->sekolah->npsn,
                'tahun_ajaran_id'	=> $semester->tahun_ajaran_id,
                'semester_id'		=> $semester->semester_id,
                'sekolah_id'		=> $user->sekolah->sekolah_id,
            ];
            $response = http_client('status', $data_sync);
            if($response && !$response->error){
                $this->online = TRUE;
                return $response->dapodik;
            }
            $this->online = FALSE;
            return FALSE;
        } catch (\Exception $e){
            $this->online = FALSE;
        }
    }
    public function render()
    {
        $timezone = config('app.timezone');
        $start = Carbon::create(date('Y'), date('m'), date('d'), '00', '00', '01', 'Asia/Jakarta');
        $end = Carbon::create(date('Y'), date('m'), date('d'), '03', '00', '00', 'Asia/Jakarta');
        $now = Carbon::now()->timezone($timezone);
        $jam_sinkron = Carbon::now()->timezone($timezone)->isBetween($start, $end, false);
        $dapodik = NULL;
        $erapor = NULL;
        if(!$jam_sinkron){
            $dapodik = ($this->data_dapodik()) ?? NULL;
            $erapor = $this->ref_erapor();
            if(!$dapodik){
                $this->online = FALSE;
            }
        }
        $this->sekolah_id = auth()->user()->sekolah_id;
        return view('livewire.sinkronisasi.dapodik', [
            'jam_sinkron' => $jam_sinkron,
            'data_sinkron' => (!$jam_sinkron) ? [
                [
                    'nama' => 'Jurusan',
                    'dapodik' => ($dapodik) ? $dapodik->jurusan : 0,
                    'erapor' => $erapor['jurusan'],
                    'sinkron' => $erapor['jurusan'],
                    'aksi' => 'jurusan',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Kurikulum',
                    'dapodik' => ($dapodik) ? $dapodik->kurikulum : 0,
                    'erapor' => $erapor['kurikulum'],
                    'sinkron' => $erapor['kurikulum'],
                    'aksi' => 'kurikulum',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Mata Pelajaran',
                    'dapodik' => ($dapodik) ? $dapodik->mata_pelajaran : 0,
                    'erapor' => $erapor['mata_pelajaran'],
                    'sinkron' => $erapor['mata_pelajaran'],
                    'aksi' => 'mata_pelajaran',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Wilayah',
                    'dapodik' => ($dapodik) ? $dapodik->wilayah : 0,
                    'erapor' => $erapor['wilayah'],
                    'sinkron' => $erapor['wilayah'],
                    'aksi' => 'wilayah',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Ref. Kompetensi Dasar',
                    'dapodik' => ($dapodik) ? $dapodik->ref_kd : 0,
                    'erapor' => $erapor['ref_kd'],
                    'sinkron' => $erapor['ref_kd'],
                    'aksi' => 'kd',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Ref. Capaian Pembelajaran',
                    'dapodik' => ($dapodik) ? $dapodik->ref_cp : 0,
                    'erapor' => $erapor['ref_cp'],
                    'sinkron' => $erapor['ref_cp_sync'],
                    'aksi' => 'cp',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Sekolah',
                    'dapodik' => 1,
                    'erapor' => $erapor['sekolah'],
                    'sinkron' => $erapor['sekolah'],
                    'aksi' => 'sekolah',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'GTK',
                    'dapodik' => ($dapodik) ? $dapodik->ptk_terdaftar : 0,
                    'erapor' => $erapor['ptk'],
                    'sinkron' => $erapor['ptk'],
                    'aksi' => 'ptk',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Rombongan Belajar',
                    'dapodik' => ($dapodik) ? $dapodik->rombongan_belajar : 0,
                    'erapor' => $erapor['rombongan_belajar'],
                    'sinkron' => $erapor['rombongan_belajar'],
                    'aksi' => 'rombongan_belajar',
                    'server' => 'dapodik',
                    'icon' => TRUE,
                    'html' => 'Jumlah Rombel Reguler &amp; Rombel Matpel Pilihan',
                ],
                [
                    'nama' => 'Peserta Didik Aktif',
                    'dapodik' => ($dapodik) ? $dapodik->registrasi_peserta_didik : 0,
                    'erapor' => $erapor['peserta_didik_aktif'],
                    'sinkron' => $erapor['peserta_didik_aktif'],
                    'aksi' => 'peserta_didik_aktif',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Peserta Didik Keluar',
                    'dapodik' => ($dapodik) ? $dapodik->siswa_keluar_dapodik : 0,
                    'erapor' => $erapor['peserta_didik_keluar'],
                    'sinkron' => $erapor['peserta_didik_keluar'],
                    'aksi' => 'peserta_didik_keluar',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Anggota Rombel Matpel Pilihan',
                    'dapodik' => ($dapodik) ? $dapodik->anggota_rombel_pilihan : 0,
                    'erapor' => $erapor['anggota_rombel_pilihan'],
                    'sinkron' => $erapor['anggota_rombel_pilihan'],
                    'aksi' => 'anggota_rombel_pilihan',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Pembelajaran',
                    'dapodik' => ($dapodik) ? $dapodik->pembelajaran_dapodik : 0,
                    'erapor' => $erapor['pembelajaran'],
                    'sinkron' => $erapor['pembelajaran'],
                    'aksi' => 'pembelajaran',
                    'server' => 'dapodik',
                    'icon' => TRUE,
                    'html' => 'Jumlah Pembelajaran Reguler &amp; Pembelajaran Matpel Pilihan',
                ],
                /*[
                    'nama' => 'Pembelajaran (Sub Mapel/Tema P5)',
                    'dapodik' => ($dapodik) ? $dapodik->sub_pembelajaran : 0,
                    'erapor' => $erapor['sub_pembelajaran'],
                    'sinkron' => $erapor['sub_pembelajaran'],
                    'aksi' => 'pembelajaran',
                    'server' => 'dapodik',
                ],*/
                [
                    'nama' => 'Ekstrakurikuler',
                    'dapodik' => ($dapodik) ? $dapodik->ekskul_dapodik : 0,
                    'erapor' => $erapor['ekstrakurikuler'],
                    'sinkron' => $erapor['ekstrakurikuler'],
                    'aksi' => 'ekstrakurikuler',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Anggota Ekstrakurikuler',
                    'dapodik' => ($dapodik) ? $dapodik->anggota_ekskul_dapodik : 0,
                    'erapor' => $erapor['anggota_ekskul'],
                    'sinkron' => $erapor['anggota_ekskul'],
                    'aksi' => 'anggota_ekskul',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
                [
                    'nama' => 'Relasi Dunia Usaha & Industri',
                    'dapodik' => ($dapodik) ? $dapodik->dudi_dapodik : 0,
                    'erapor' => $erapor['dudi'],
                    'sinkron' => $erapor['dudi'],
                    'aksi' => 'dudi',
                    'server' => 'dapodik',
                    'icon' => FALSE,
                    'html' => NULL,
                ],
            ] : NULL,
            'breadcrumbs' => [
                ['link' => "/", 'name' => "Beranda"], ['link' => '#', 'name' => 'Sinkronisasi'], ['name' => 'Ambil Data Dapodik']
            ]
        ]);
    }
    private function ref_erapor(){
        $sekolah = Sekolah::withCount([
            'ptk' => function($query){
                $query->where('is_dapodik', 1);
                if(Schema::hasTable('ptk_keluar')){
                    $query->whereDoesntHave('ptk_keluar', function($query){
                        $query->where('semester_id', session('semester_aktif'));
                    });
                }
            }, 
            'rombongan_belajar' => function($query){
                $query->where('semester_id', session('semester_aktif'));
                $query->whereIn('jenis_rombel', [1, 8, 9, 16]);
            },
            'peserta_didik as pd_aktif_count' => function($query){
                $query->whereHas('anggota_rombel', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                    $query->whereHas('rombongan_belajar', function($query){
                        $query->where('jenis_rombel', 1);
                    });
                });
            },
            'peserta_didik as pd_keluar_count' => function($query){
                $query->whereHas('pd_keluar', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                });
            },
            'anggota_rombel as anggota_rombel_pilihan' => function($query){
                $query->where('semester_id', session('semester_aktif'));
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('jenis_rombel', 16);
                });
            },
            'pembelajaran' => function($query){
                $query->where('semester_id', session('semester_aktif'));
            },
            'ekstrakurikuler' => function($query){
                $query->where('semester_id', session('semester_aktif'));
            },
            'anggota_rombel as anggota_ekskul_count' => function($query){
                $query->where('semester_id', session('semester_aktif'));
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('semester_id', session('semester_aktif'));
                    $query->where('jenis_rombel', 51);
                });
                $query->whereHas('peserta_didik', function($query){
                    $query->doesntHave('pd_keluar');
                });
            },
            'dudi'
        ])->find(session('sekolah_id'));
        $ref_cp_sync = NULL;
        try {
            $ref_cp_sync = Capaian_pembelajaran::where(function($query){
                $query->whereIsDir(1);
            })->count();
        } catch (\Exception $e){
            $ref_cp_sync = '<a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="Jalankan<br><strong>php artisan erapor:update</strong>">
            <i class="fa-regular fa-circle-question"></i>
        </a>';
        }
        return [
            'sekolah' => $sekolah->sinkron,
            'ptk' => $sekolah->ptk_count,
            'rombongan_belajar' => $sekolah->rombongan_belajar_count,
            'peserta_didik_aktif' => $sekolah->pd_aktif_count,
            'peserta_didik_keluar' => $sekolah->pd_keluar_count,
            'anggota_rombel_pilihan' => $sekolah->anggota_rombel_pilihan,
            'pembelajaran' => $sekolah->pembelajaran_count,
            'ekstrakurikuler' => $sekolah->ekstrakurikuler_count,
            'anggota_ekskul' => $sekolah->anggota_ekskul_count,
            'dudi' => $sekolah->dudi_count,
            'jurusan' => Jurusan::select(DB::raw('TRIM(jurusan_id)'))
            ->groupByRaw('TRIM(jurusan_id)')->get()->count(),
            'kurikulum' => Kurikulum::count(),
            'mata_pelajaran' => Mata_pelajaran::count(),
            'mata_pelajaran_kurikulum' => Mata_pelajaran_kurikulum::count(),
            'wilayah' => Mst_wilayah::count(),
            'ref_kd' => Kompetensi_dasar::withTrashed()->count(),
            'ref_cp' => Capaian_pembelajaran::count(),
            'ref_cp_sync' => $ref_cp_sync,
            //'mata_pelajaran_kurikulum' => Mata_pelajaran_kurikulum::count(),
        ];
    }
    public function clickSync()
    {
        $this->showSyncButton =! $this->showSyncButton;
        $this->emit('delaySync');
    }
    public function delaySync(){
        if(!$this->prosesSync){
            $this->emit('prosesSync');
        }
    }
    public function syncSatuan($server, $satuan){
        $this->server = $server;
        $this->satuan = $satuan;
        $this->emit('delaySync');
    }
    public function prosesSync(){
        $this->prosesSync = TRUE;
        if($this->satuan == 'all'){
            $list_data = [
                'jurusan', 
                'kurikulum', 
                'mata_pelajaran', 
                'mata_pelajaran_kurikulum', 
                'sekolah',
                'ptk', 
                'rombongan_belajar', 
                'peserta_didik_aktif', 
                'peserta_didik_keluar', 
                'pembelajaran', 
                'ekstrakurikuler', 
                'anggota_ekskul', 
                'dudi'
            ];
            foreach($list_data as $data){
                Artisan::call('sinkron:dapodik', ['satuan' => $data, 'akses' => 1]);
            }
        } else {
            if($this->server == 'erapor'){
                $argumen = ['satuan' => $this->satuan, 'email' => $this->loggedUser()->email, 'akses' => 1];
            } else {
                $argumen = ['satuan' => $this->satuan, 'akses' => 1];
            }
            Artisan::call('sinkron:'.$this->server, $argumen);
        }
        $this->respon_artisan = Artisan::output();
        $this->emit('finishSync');
    }
    private function loggedUser(){
        return auth()->user();
    }
    private function hapus_file(){
        Storage::disk('public')->delete('proses_sync_'.session('sekolah_id').'.json');
		/*$json_files = Storage::disk('public')->files('kd');
		Storage::disk('public')->delete($json_files);*/
    }
    public function finishSync(){
        $response = Str::of($this->respon_artisan)->between('{', '}');
        $response = json_decode('{'.$response.'}');
        if($response){
            $status = $response->status;
            $title = $response->title;
            $message = $response->message;
        } else {
            $status = 'success';
            $title = 'Berhasil';
            $message = 'Pengambilan data Dapodik berhasil';
        }
        $this->reset(['prosesSync', 'server', 'satuan']);
        $this->alert($status, $title, [
            'text' => $message,
            'allowOutsideClick' => false,
            'toast' => false,
            'timer' => null,
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'onConfirmed' => 'confirmed',
        ]);
    }
    public function confirmed(){
        $this->hapus_file();
    }
    /*
    public function syncSatuan($server, $aksi){
        $this->server = $server;
        $this->aksi = $aksi;
        $this->showSyncButton =! $this->showSyncButton;
    }
    
    */
}
