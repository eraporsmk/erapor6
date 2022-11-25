<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Mst_wilayah;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Jurusan_sp;
use App\Models\Gelar;
use App\Models\Gelar_ptk;
use App\Models\Rombongan_belajar;
use App\Models\Peserta_didik;
use App\Models\Pd_keluar;
use App\Models\Anggota_rombel;
use App\Models\Mata_pelajaran;
use App\Models\Mata_pelajaran_kurikulum;
use App\Models\Pembelajaran;
use App\Models\Kurikulum;
use App\Models\Ekstrakurikuler;
use App\Models\Dudi;
use App\Models\Mou;
use App\Models\Akt_pd;
use App\Models\Anggota_akt_pd;
use App\Models\Bimbing_pd;
use App\Models\User;
use App\Models\Jabatan_ptk;
use Carbon\Carbon;
use Storage;

class SinkronDapodik extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinkron:dapodik {satuan?} {akses?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $list_data = [
            'semua_data',
            'jurusan', 
            'kurikulum', 
            'mata_pelajaran', 
            //'mata_pelajaran_kurikulum',
            'wilayah', 
            'kompetensi-dasar', 
            'capaian-pembelajaran',
            'sekolah', 
            'ptk', 
            'rombongan_belajar', 
            'peserta_didik_aktif', 
            'peserta_didik_keluar', 
            'anggota_rombel_pilihan',
            'pembelajaran', 
            'ekstrakurikuler', 
            'anggota_ekskul', 
            'dudi'
        ];
        if($this->argument('satuan')){
            $satuan = $this->argument('satuan');
            $semester = Semester::find(session('semester_aktif'));
            $sekolah = Sekolah::with(['user' => function($query) use ($semester){
                $query->whereRoleIs('admin', $semester->nama);
            }])->find(session('sekolah_id'));
        } else {
            $email = $this->ask('Email Administrator:');
            $user = User::where('email', $email)->first();
            $sekolah = NULL;
            if($user){
                $semester = Semester::where('periode_aktif', 1)->first();
                if($user->hasRole('admin', $semester->nama)){
                    $sekolah = Sekolah::with(['user' => function($query) use ($semester){
                        $query->whereRoleIs('admin', $semester->nama);
                    }])->find($user->sekolah_id);
                    $satuan = $this->choice(
                        'Pilih data untuk di sinkronisasi!',
                        $list_data
                    );
                } else {
                    $this->error('Email '.$email.' tidak memiliki akses Administrator');
                    exit;
                }
            } else {
                $this->error('Email '.$email.' tidak terdaftar');
                exit;
            }
        }
        if($sekolah){
            $data = collect($list_data);
            if($satuan != 'semua_data'){
                if($data->contains($satuan)){
                    $dapodik = $this->ambil_data($sekolah, $semester, $satuan);
                    if($dapodik){
                        $this->proses_data($dapodik, $satuan, $sekolah->user, $semester);
                        if($this->argument('akses')){
                            $this->call('respon:artisan', ['status' => 'info', 'title' => 'Berhasil', 'respon' => 'Sinkronisasi '.$this->get_table($satuan).' berhasil']);
                        }
                        $this->info("\n".'Sinkronisasi '.$this->get_table($satuan).' berhasil');
                    }
                } else {
                    $this->error('Permintaan '.$satuan.' tidak ditemukan');
                }
            } else {
                $data->forget(0);
                foreach($data as $d){
                    $dapodik = $this->ambil_data($sekolah, $semester, $d);
                    if($dapodik){
                        $this->proses_data($dapodik, $d, $sekolah->user, $semester);
                        if($this->argument('akses')){
                            $this->call('respon:artisan', ['status' => 'info', 'title' => 'Berhasil', 'respon' => 'Sinkronisasi '.$this->get_table($satuan).' berhasil']);
                        }
                        $this->info("\n".'Sinkronisasi '.$this->get_table($d).' berhasil');
                    } else {
                        $this->error('Pengambilan data '.$this->get_table($d).' gagal. Server tidak merespon 1');
                    }
                }
            }
        } else {
            $this->error('Sekolah tidak ditemukan');
        }
    }
    private function get_table($table){
        $list_data = [
            'jurusan' => 'Referensi Jurusan', 
            'kurikulum' => 'Referensi Kurikulum', 
            'mata_pelajaran' => 'Referensi Mata Pelajaran', 
            //'mata_pelajaran_kurikulum' => 'Referensi Mata Pelajaran Kurikulum', 
            'semua_data' => 'Semua Referensi e-Rapor SMK', 
            'wilayah' => 'Referensi Wilayah', 
            'kompetensi-dasar' => 'Referensi Kompetensi Dasar', 
            'capaian-pembelajaran' => 'Referensi Capaian Pembelajaran',
            'sekolah' => 'Data Sekolah',
            'jurusan_sp' => 'Data Jurusan SP',
            'ptk' => 'Data PTK', 
            'rombongan_belajar' => 'Data Rombongan Belajar', 
            'peserta_didik_aktif' => 'Data Peserta Didik Aktif', 
            'anggota_rombel_pilihan' => 'Anggota Rombel Matpel Pilihan',
            'peserta_didik_keluar' => 'Data Peserta Didik Keluar', 
            'pembelajaran' => 'Data Pembelajaran', 
            'ekstrakurikuler' => 'Data Ekstrakurikuler', 
            'anggota_ekskul' => 'Data Anggota Ekskul', 
            'dudi' => 'Data DUDI'
        ];
        if(isset($list_data[$table])){
            return $list_data[$table];
        }
        return $table;
    }
    private function url_server($server, $ep){
        return config('erapor.'.$server).$ep;
    }
    private function proses_sync($title, $table, $inserted, $jumlah, $sekolah_id){
        $record['table'] = $title.' '.$this->get_table($table);
		$record['jumlah'] = $jumlah;
		$record['inserted'] = $inserted;
		Storage::disk('public')->put('proses_sync_'.$sekolah_id.'.json', json_encode($record));
    }
    private function ambil_data($sekolah, $semester, $satuan){
        $this->info("\n".'Mengambil '.$this->get_table($satuan));
        $this->proses_sync('Mengambil', $satuan, 0, 0, $sekolah->sekolah_id);
        $server_dashboard = [
            'wilayah', 
            'kompetensi-dasar', 
            'capaian-pembelajaran',
        ];
        if(in_array($satuan, $server_dashboard)){
            $this->call('sinkron:erapor', ['satuan' => $satuan, 'email' => $sekolah->user->email]);
        } else {
            try {
                $updated_at = NULL;
                if($satuan == 'mata_pelajaran_kurikulum'){
                    $updated_at = Mata_pelajaran_kurikulum::orderBy('updated_at', 'DESC')->first()->created_at;
                }
                if($sekolah->user){
                    $data_sync = [
                        'username_dapo'		=> $sekolah->user->email,
                        'password_dapo'		=> $sekolah->user->password,
                        'npsn'				=> $sekolah->npsn,
                        'tahun_ajaran_id'	=> $semester->tahun_ajaran_id,
                        'semester_id'		=> $semester->semester_id,
                        'sekolah_id'		=> $sekolah->sekolah_id,
                        'updated_at'        => ($updated_at) ? Carbon::parse($updated_at)->format('Y-m-d H:i:s') : NULL,
                        'last_sync'         => NULL,
                    ];
                    $response = Http::withHeaders([
                        'x-api-key' => $sekolah->sekolah_id,
                    ])->withBasicAuth('admin', '1234')->asForm()->post($this->url_server('dapodik', 'api/'.$satuan), $data_sync);
                    if($response->status() == 200){
                        $this->info('Memproses '.$this->get_table($satuan));
                        return $response->object();
                    } else {
                        if($this->argument('akses')){
                            $this->call('respon:artisan', ['status' => 'error', 'title' => 'Gagal', 'respon' => 'Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon. Status Server: '.$response->status()]);
                        }
                        $this->proses_sync('', 'Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon', 0, 0, 0);
                        return $this->error('Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon. Status Server: '.$response->status());
                        return false;
                    }
                } else {
                    return $this->error('Sekolah tidak memiliki pengguna Admin');
                }
            } catch (\Exception $e){
                $this->proses_sync('', 'Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon', 0, 0, 0);
                return $this->error('Proses pengambilan data '.$this->get_table($satuan).' gagal. Server tidak merespon 3');
            }
        }
    }
    private function proses_data($dapodik, $satuan, $user, $semester){
        $function = 'simpan_'.$satuan;
        $this->{$function}($dapodik->dapodik, $user, $semester);
    }
    private function ambil_mapel_kur($user, $sekolah, $semester, $satuan, $counter){
        $updated_at = Mata_pelajaran_kurikulum::orderBy('updated_at', 'DESC')->first()->created_at;
        $data_sync = [
            'username_dapo'		=> $user->email,
            'password_dapo'		=> $user->password,
            'npsn'				=> $sekolah->npsn,
            'tahun_ajaran_id'	=> $semester->tahun_ajaran_id,
            'semester_id'		=> $semester->semester_id,
            'sekolah_id'		=> $sekolah->sekolah_id,
            'updated_at'        => ($updated_at) ? Carbon::parse($updated_at)->format('Y-m-d H:i:s') : NULL,
            'last_sync'         => NULL,
        ];
        $response = Http::withHeaders([
            'x-api-key' => $sekolah->sekolah_id,
        ])->withBasicAuth('admin', '1234')->asForm()->post($this->url_server('dapodik', 'api/'.$satuan.'/'.$counter), $data_sync);
        if($response->status() == 200){
            $this->info('Memproses Mata Pelajaran Kurikulum '.$counter);
            return $response->object();
        } else {
            $this->proses_sync_mapel_kur('', 'Proses pengambilan data Mata Pelajaran Kurikulum gagal. Server tidak merespon', 0, 0, 0);
            return $this->error('Proses pengambilan data Mata Pelajaran Kurikulum gagal. Server tidak merespon 4');
            return false;
        }
    }
    private function proses_sync_mapel_kur($title, $table, $inserted, $jumlah, $sekolah_id){
        $record['table'] = $title.' '.$table;
		$record['jumlah'] = $jumlah;
		$record['inserted'] = $inserted;
		//Storage::disk('public')->put('proses_sync.json', json_encode($record));
        Storage::disk('public')->put('proses_sync_'.$sekolah_id.'.json', json_encode($record));
    }
    private function simpan_mata_pelajaran_kurikulum($dapodik, $user, $semester){
        $limit = 500;
        $bar = $this->output->createProgressBar($dapodik->total_rows);
        $bar->start();
        $i=1;
        if($dapodik->total_rows > $limit){
            for ($counter = 0; $counter <= $dapodik->total_rows; $counter += $limit) {
                if($counter){
                    foreach($dapodik->dapodik as $data){
                        $this->simpan_mapel_kur($data);
                        $this->proses_sync_mapel_kur('Memperoses', 'mata_pelajaran_kurikulum', $i, $dapodik->total_rows, $user->sekolah_id);
                        $i++;
                        $bar->advance();
                    }
                    $ambil = $this->ambil_mapel_kur($user, $user->sekolah, $semester, 'mata_pelajaran_kurikulum', $counter);
                } else {
                    foreach($dapodik->dapodik as $data){
                        $this->simpan_mapel_kur($data);
                        $this->proses_sync_mapel_kur('Memperoses', 'mata_pelajaran_kurikulum', $i, $dapodik->total_rows, $user->sekolah_id);
                        $i++;
                        $bar->advance();
                    }
                }
            }
        }
        $bar->finish();
    }
    private function simpan_mapel_kur($data){
        $kurikulum = Kurikulum::find($data->kurikulum_id);
        $mata_pelajaran = Mata_pelajaran::find($data->mata_pelajaran_id);
        if($kurikulum && $mata_pelajaran){
            Mata_pelajaran_kurikulum::updateOrCreate(
                [
                    'kurikulum_id' => $data->kurikulum_id,
                    'mata_pelajaran_id' => $data->mata_pelajaran_id,
                    'tingkat_pendidikan_id' => $data->tingkat_pendidikan_id,
                ],
                [
                    'jumlah_jam' => $data->jumlah_jam,
                    'jumlah_jam_maksimum' => $data->jumlah_jam_maksimum,
                    'wajib' => $data->wajib,
                    'sks' => $data->sks,
                    'a_peminatan' => $data->a_peminatan,
                    'area_kompetensi' => $data->area_kompetensi,
                    'gmp_id' => $data->gmp_id,
                    'created_at' => $data->create_date,
                    'updated_at' => $data->last_update,
                    'deleted_at' => $data->expired_date,
                    'last_sync' => $data->last_sync,
                ]
            );
        }
    }
    private function simpan_peserta_didik_aktif($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'peserta_didik_aktif', $i, count($dapodik), $user->sekolah_id);
        $anggota_rombel_id = [];
        foreach($dapodik as $data){
            $anggota_rombel_id[] = $data->anggota_rombel_id;
            $this->simpan_pd($data, $user, $semester, NULL);
            $this->proses_sync('Memperoses', 'peserta_didik_aktif', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
        Anggota_rombel::whereHas('rombongan_belajar', function($query) use ($semester){
            $query->where('semester_id', $semester->semester_id);
            $query->where('jenis_rombel', 1);
        })->whereNotIn('anggota_rombel_id', $anggota_rombel_id)->where('semester_id', $semester->semester_id)->where('sekolah_id', $user->sekolah_id)->delete();
    }
    private function simpan_peserta_didik_keluar($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'peserta_didik_keluar', $i, count($dapodik), $user->sekolah_id);
        $peserta_didik_id = [];
        foreach($dapodik as $data){
            $peserta_didik_id[] = $data->peserta_didik_id;
            $this->simpan_pd($data, $user, $semester, now());
            Pd_keluar::updateOrCreate(
                [
                    'peserta_didik_id' => $data->peserta_didik_id,
                ],
                [
                    'sekolah_id' => $user->sekolah_id,
                    'semester_id' => $semester->semester_id,
                    'last_sync' => now(),
                ]
            );
            $this->proses_sync('Memperoses', 'peserta_didik_keluar', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    private function simpan_pd($data, $user, $semester, $deleted_at){
        $wilayah = NULL;
        if(isset($data->wilayah)){
            $this->proses_wilayah($data->wilayah);
        }
        if($wilayah){
            $kecamatan = ($wilayah['kecamatan']) ? $wilayah['kecamatan']->nama : 0;
        } else {
            $kecamatan = $data->kecamatan;
        }
        Peserta_didik::withTrashed()->updateOrCreate(
            [
                'peserta_didik_id' => $data->peserta_didik_id
            ],
            [
                'peserta_didik_id_dapodik' => $data->peserta_didik_id,
                'sekolah_id'		=> $user->sekolah_id,
                'nama' 				=> $data->nama,
                'no_induk' 			=> ($data->nipd) ?? 0,
                'nisn' 				=> $data->nisn,
                'jenis_kelamin' 	=> ($data->jenis_kelamin) ?? 0,
                'tempat_lahir' 		=> ($data->tempat_lahir) ?? 0,
                'tanggal_lahir' 	=> $data->tanggal_lahir,
                'agama_id' 			=> ($data->agama_id) ?? 0,
                'status' 			=> 'Anak Kandung',
                'anak_ke' 			=> ($data->anak_keberapa) ?? 0,
                'alamat' 			=> ($data->alamat_jalan) ?? 0,
                'rt' 				=> ($data->rt) ?? 0,
                'rw' 				=> ($data->rw) ?? 0,
                'desa_kelurahan' 	=> ($data->desa_kelurahan) ?? 0,
                'kecamatan' 		=> $kecamatan,
                'kode_pos' 			=> ($data->kode_pos) ?? 0,
                'no_telp' 			=> ($data->nomor_telepon_seluler) ?? 0,
                'sekolah_asal' 		=> ($data->sekolah_asal) ?? 0,
                'diterima' 			=> ($data->tanggal_masuk_sekolah) ?? 0,
                'kode_wilayah' 		=> $data->kode_wilayah,
                'email' 			=> $data->email,
                'nama_ayah' 		=> ($data->nama_ayah) ?? 0,
                'nama_ibu' 			=> ($data->nama_ibu_kandung) ?? 0,
                'kerja_ayah' 		=> ($data->pekerjaan_id_ayah) ? $data->pekerjaan_id_ayah : 1,
                'kerja_ibu' 		=> ($data->pekerjaan_id_ibu) ? $data->pekerjaan_id_ibu : 1,
                'nama_wali' 		=> ($data->nama_wali) ?? 0,
                'alamat_wali' 		=> ($data->alamat_jalan) ?? 0,
                'telp_wali' 		=> ($data->nomor_telepon_seluler) ?? 0,
                'kerja_wali' 		=> ($data->pekerjaan_id_wali) ? $data->pekerjaan_id_wali : 1,
                'active' 			=> 1,
                'last_sync'			=> now(),
            ]
        );
        $find = Rombongan_belajar::find($data->rombongan_belajar_id);
        if($find){
            $this->simpan_anggota_rombel($data, $user, $semester, $deleted_at);
        }
    }
    private function simpan_ekstrakurikuler($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'ekstrakurikuler', $i, count($dapodik), $user->sekolah_id);
        $id_kelas_ekskul = [];
        foreach($dapodik as $data){
            $id_kelas_ekskul[] = $data->ID_kelas_ekskul;
            $this->insert_rombel($data, $user, $semester, TRUE);
            Ekstrakurikuler::withTrashed()->updateOrCreate(
                [
                    'ekstrakurikuler_id' => $data->ID_kelas_ekskul,
                ],
                [
                    'id_kelas_ekskul' => $data->ID_kelas_ekskul,
                    'semester_id' => $data->semester_id,
                    'sekolah_id'	=> $data->sekolah_id,
                    'guru_id' => $data->ptk_id,
                    'nama_ekskul' => $data->nm_ekskul,
                    'is_dapodik' => 1,
                    'rombongan_belajar_id'	=> $data->rombongan_belajar_id,
                    'alamat_ekskul' => $data->rombongan_belajar->ruang->nm_ruang, 
                    'last_sync'	=> now(),
                ]
            );
            $this->proses_sync('Memperoses', 'ekstrakurikuler', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
        if($id_kelas_ekskul){
            Ekstrakurikuler::where('sekolah_id', $user->sekolah_id)->where('semester_id', $semester->semester_id)->whereNotIn('id_kelas_ekskul', $id_kelas_ekskul)->delete();
        }
    }
    private function insert_rombel($data, $user, $semester, $ekskul){
        if($data->jurusan_sp_id){
            $this->insert_jurusan_sp($data->jurusan_sp, $user, $semester);
        }
        if(isset($data->Soft_delete)){
            $soft_delete = ($data->Soft_delete) ? now() : NULL;
        } else {
            $soft_delete = ($data->soft_delete) ? now() : NULL;
        }
        $guru = Guru::withTrashed()->find($data->ptk_id);
        if($guru){
            $jurusan = NULL;
            $jurusan_sp = NULL;
            if($data->jurusan_sp_id){
                $jurusan = Jurusan::find($data->jurusan_sp->jurusan_id);
                $jurusan_sp = Jurusan_sp::find($data->jurusan_sp_id);
            }
            $kurikulum = Kurikulum::find($data->kurikulum_id);
            if($kurikulum){
                Rombongan_belajar::withTrashed()->updateOrCreate(
                    [
                        'rombongan_belajar_id' => $data->rombongan_belajar_id,
                    ],
                    [
                        'sekolah_id' => $data->sekolah_id,
                        'semester_id' => $data->semester_id,
                        'jurusan_id' => ($jurusan) ? $data->jurusan_sp->jurusan_id : NULL,
                        'jurusan_sp_id' => ($jurusan_sp) ? $data->jurusan_sp_id : NULL,
                        'kurikulum_id' => $data->kurikulum_id,
                        'nama' => ($ekskul) ? $data->nm_ekskul : $data->nama,
                        'guru_id' => $data->ptk_id,
                        'ptk_id' => $data->ptk_id,
                        'tingkat' => $data->tingkat_pendidikan_id,
                        'jenis_rombel' => $data->jenis_rombel,
                        'rombel_id_dapodik' => $data->rombongan_belajar_id,
                        'deleted_at' => $soft_delete,
                        //'deleted_at' => ($data->soft_delete) ? now() : NULL,
                        'last_sync' => now(),
                    ]
                );
            }
        }
    }
    private function simpan_rombongan_belajar($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
        $this->proses_sync('Memperoses', 'rombongan_belajar', $i, count($dapodik), $user->sekolah_id);
        $rombongan_belajar_id = [];
        foreach($dapodik as $data){
            $rombongan_belajar_id[] = $data->rombongan_belajar_id;
            $this->insert_rombel($data, $user, $semester, FALSE);
            $this->proses_sync('Memperoses', 'rombongan_belajar', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
        if($rombongan_belajar_id){
            Rombongan_belajar::where('sekolah_id', $user->sekolah_id)->where('semester_id', $semester->semester_id)->whereNotIn('rombongan_belajar_id', $rombongan_belajar_id)->delete();
        }
    }
    private function simpan_ptk($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
        $this->proses_sync('Memperoses', 'ptk', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $this->simpan_guru($data, $user, $semester);
            $this->proses_sync('Memperoses', 'ptk', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    private function ambil_referensi($data_sync){
        return NULL;
        //$server = 'http://jembatan.test/api';
        $server = 'http://api.erapor-smk.net/api';
        $response = Http::withBasicAuth('masadi', '@Bismill4h#')->post($server.'/dapodik', $data_sync);
        return $response->object();
    }
    private function simpan_gelar($data){
        $gelar = Gelar::find($data->gelar_akademik_id);
        return $gelar;
        if(!$gelar){
            $data_sync = [
                'server' => 'local',
                'table_utama' => [
                    'table' => 'ref.gelar_akademik',
                    'primary_key' => 'gelar_akademik_id', 
                ],
                'table_join' => NULL, 
                'satuan' => TRUE,
                'select' => NULL,
                'where' => [
                    [
                        'field' => 'gelar_akademik_id',
                        'value' => $data->gelar_akademik_id,
                    ]
                ]
            ];
            $gelar = $this->ambil_referensi($data_sync);
        }
        return $gelar;
    }
    private function simpan_guru($data, $user, $semester){
        $this->proses_wilayah($data->wilayah);
        $random = Str::random(6);
		$data->email = ($data->email) ? $data->email : strtolower($random).'@erapor-smk.net';
        $data->email = ($data->email != $user->email) ? $data->email : strtolower($random).'@erapor-smk.net';
		$data->email = strtolower($data->email);
		$data->nuptk = ($data->nuptk) ? $data->nuptk : mt_rand();
        $jenis_ptk_id = 0;
        $jabatan_ptk_id = NULL;
        if(isset($data->jenis_ptk_id)){
            $jenis_ptk_id = $data->jenis_ptk_id;
        }
        if(isset($data->jabatan_ptk_id)){
            $jabatan_ptk_id = $data->jabatan_ptk_id;
        }
        $create_guru = Guru::withTrashed()->updateOrCreate(
			[
                'guru_id' => $data->ptk_id
            ],
			[
                'guru_id_dapodik'       => $data->ptk_id,
                'sekolah_id' 			=> $data->sekolah_id,
                'nama' 					=> $data->nama,
                'nuptk' 				=> $data->nuptk,
                'nip' 					=> $data->nip,
                'nik' 					=> $data->nik,
                'jenis_kelamin' 		=> $data->jenis_kelamin,
                'tempat_lahir' 			=> $data->tempat_lahir,
                'tanggal_lahir' 		=> $data->tanggal_lahir,
                'status_kepegawaian_id'	=> $data->status_kepegawaian_id,
                'jenis_ptk_id' 			=> $jenis_ptk_id,
                'jabatan_ptk_id' 		=> $jabatan_ptk_id,
                'agama_id' 				=> $data->agama_id,
                'alamat' 				=> $data->alamat_jalan,
                'rt' 					=> $data->rt,
                'rw' 					=> $data->rw,
                'desa_kelurahan' 		=> $data->desa_kelurahan,
                'kecamatan' 			=> $data->wilayah->nama,
                'kode_wilayah'			=> $data->kode_wilayah,
                'kode_pos'				=> ($data->kode_pos) ? $data->kode_pos : 0,
                'no_hp'					=> ($data->no_hp) ? $data->no_hp : 0,
                'email' 				=> $data->email,
                'is_dapodik'			=> 1,
                'last_sync'				=> now(),
            ]
		);
        if(isset($data->rwy_pend_formal)){
            $gelar_ptk_id = [];
            foreach($data->rwy_pend_formal as $rwy_pend_formal){
                $gelar_ptk_id[] = $rwy_pend_formal->riwayat_pendidikan_formal_id;
                $riwayat_pendidikan_formal_id = strtolower($rwy_pend_formal->riwayat_pendidikan_formal_id);
                $ptk_id = $rwy_pend_formal->ptk_id;
                $gelar = $this->simpan_gelar($rwy_pend_formal);
                if($gelar){
                    Gelar_ptk::withTrashed()->updateOrCreate(
                        [
                            'gelar_ptk_id' => $riwayat_pendidikan_formal_id,
                        ],
                        [
                            'guru_id' => $rwy_pend_formal->ptk_id,
                            'gelar_akademik_id' => $rwy_pend_formal->gelar_akademik_id,
                            'sekolah_id' => $user->sekolah_id,
                            'ptk_id' => $rwy_pend_formal->ptk_id,
                            'deleted_at' => ($rwy_pend_formal->Soft_delete) ? now() : NULL,
                            'last_sync' => now(),
                        ]
                    );
                }
            }
            if($gelar_ptk_id){
                Gelar_ptk::where('ptk_id', $data->ptk_id)->whereNotIn('gelar_ptk_id', $gelar_ptk_id)->delete();
            }
        }
        return $create_guru;
    }
    private function simpan_sekolah($data, $user, $semester){
        $kepala_sekolah = $this->simpan_guru($data->kepala_sekolah, $user, $semester);
        $sekolah = Sekolah::find($data->sekolah_id);
        $wilayah = $this->proses_wilayah($data->wilayah);
        $sekolah->npsn = $data->npsn;
		$sekolah->nama = $data->nama;
		$sekolah->nss = $data->nss;
		$sekolah->alamat = $data->alamat_jalan;
		$sekolah->desa_kelurahan = $data->desa_kelurahan;
		$sekolah->kecamatan = ($wilayah['kecamatan']) ? $wilayah['kecamatan']->nama : NULL;
		$sekolah->kode_wilayah = $data->kode_wilayah;
		$sekolah->kabupaten = ($wilayah['kabupaten']) ? $wilayah['kabupaten']->nama : NULL;
		$sekolah->provinsi = ($wilayah['provinsi']) ? $wilayah['provinsi']->nama : NULL;
		$sekolah->kode_pos = $data->kode_pos;
		$sekolah->lintang = $data->lintang;
		$sekolah->bujur = $data->bujur;
		$sekolah->no_telp = $data->nomor_telepon;
		$sekolah->no_fax = $data->nomor_fax;
		$sekolah->email = $data->email;
		$sekolah->website = $data->website;
		$sekolah->status_sekolah = $data->status_sekolah;
		$sekolah->last_sync = now();
        $sekolah->guru_id = ($kepala_sekolah) ? $kepala_sekolah->guru_id : NULL;
		$sekolah->sinkron = 1;
		$sekolah->save();
        $this->call('sinkron:referensi');
        $this->simpan_jurusan_sp($data->jurusan_sp, $user, $semester);
    }
    private function insert_jurusan_sp($data, $user, $semester){
        $find = Jurusan::find($data->jurusan_id);
        if($find){
            Jurusan_sp::withTrashed()->updateOrCreate(
                [
                    'jurusan_sp_id' => $data->jurusan_sp_id,
                ],
                [
                    'jurusan_sp_id_dapodik' => $data->jurusan_sp_id,
                    'sekolah_id' => $data->sekolah_id,
                    'jurusan_id' => $data->jurusan_id,
                    'nama_jurusan_sp' => $data->nama_jurusan_sp,
                    'last_sync' => now(),
                ]
            );
        }
    }
    private function simpan_jurusan_sp($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
        $this->proses_sync('Memperoses', 'jurusan_sp', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $jurusan_sp_id[] = $data->jurusan_sp_id;
            $this->insert_jurusan_sp($data, $user, $semester);
            $bar->advance();
            $i++;
        }
        $bar->finish();
        Jurusan_sp::where('sekolah_id', $user->sekolah_id)->whereNotIn('jurusan_sp_id', $jurusan_sp_id)->delete();
    }
    private function simpan_jurusan($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
        $this->proses_sync('Memperoses', 'jurusan', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $this->insert_jurusan($data, $user, $semester);
            $this->proses_sync('Memperoses', 'jurusan', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    private function insert_jurusan($data, $user, $semester){
        $jurusan_induk = NULL;
        if($data->jurusan_induk){
            $jurusan_induk = Jurusan::find($data->jurusan_induk);
        }
        Jurusan::updateOrCreate(
            [
                'jurusan_id' => $data->jurusan_id
            ],
            [
                'nama_jurusan' => $data->nama_jurusan,
                'untuk_sma' => $data->untuk_sma,
                'untuk_smk' => $data->untuk_smk,
                'untuk_pt' => $data->untuk_pt,
                'untuk_slb' => $data->untuk_slb,
                'untuk_smklb' => $data->untuk_smklb,
                'jenjang_pendidikan_id' => $data->jenjang_pendidikan_id,
                'jurusan_induk' => ($jurusan_induk) ? $data->jurusan_induk : NULL,
                'level_bidang_id' => $data->level_bidang_id,
                'deleted_at' => $data->expired_date,
                'last_sync' => now(),
            ]
        );
    }
    private function proses_wilayah($wilayah){
        $kecamatan = NULL;
        $kabupaten = NULL;
        $provinsi = NULL;
        if($wilayah->id_level_wilayah == 4){
			if($wilayah->parrent_recursive){
				if($wilayah->parrent_recursive->parrent_recursive){
					if($wilayah->parrent_recursive->parrent_recursive->parrent_recursive){
                        $provinsi = $this->update_wilayah($wilayah->parrent_recursive->parrent_recursive->parrent_recursive);
                        $kabupaten = $this->update_wilayah($wilayah->parrent_recursive->parrent_recursive);
                        $kecamatan = $this->update_wilayah($wilayah->parrent_recursive);
                        $desa = $this->update_wilayah($wilayah);
                    }
				}
			}
		} else {
			$kecamatan = $wilayah->nama;
			if($wilayah->parrent_recursive){
				$kabupaten = $wilayah->parrent_recursive->nama;
				if($wilayah->parrent_recursive->parrent_recursive){
					$provinsi = $this->update_wilayah($wilayah->parrent_recursive->parrent_recursive);
                    $kabupaten = $this->update_wilayah($wilayah->parrent_recursive);
                    $kecamatan = $this->update_wilayah($wilayah->parrent_recursive);
				}
			}
		}
        return [
            'kecamatan' => $kecamatan,
            'kabupaten' => $kabupaten,
            'provinsi' => $provinsi,
        ];
    }
    private function update_wilayah($wilayah){
        $data = Mst_wilayah::updateOrCreate(
            [
                'kode_wilayah' => $wilayah->kode_wilayah,
            ],
            [
                'nama' => $wilayah->nama,
                'id_level_wilayah' => $wilayah->id_level_wilayah,
                'mst_kode_wilayah' => $wilayah->mst_kode_wilayah,
                'negara_id' => $wilayah->negara_id,
                'asal_wilayah' => $wilayah->asal_wilayah,
                'kode_bps' => $wilayah->kode_bps,
                'kode_dagri' => $wilayah->kode_dagri,
                'kode_keu' => $wilayah->kode_keu,
                'deleted_at' => $wilayah->expired_date,
                'last_sync' => $wilayah->last_sync,
            ]
        );
        return $data;
    }
    private function simpan_pembelajaran($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'pembelajaran', $i, count($dapodik), $user->sekolah_id);
        $pembelajaran_id = [];
        foreach($dapodik as $data){
            $pembelajaran_id[] = $data->pembelajaran_id;
            $induk_pembelajaran_id = NULL;
            $induk = NULL;
            if(isset($data->induk_pembelajaran_id)){
                $induk_pembelajaran_id = $data->induk_pembelajaran_id;
                $induk = Pembelajaran::withTrashed()->find($induk_pembelajaran_id);
            }
            $find = Rombongan_belajar::find($data->rombongan_belajar_id);
            if($find){
                Pembelajaran::withTrashed()->updateOrCreate(
                    [
                        'pembelajaran_id' => $data->pembelajaran_id
                    ],
                    [
                        'pembelajaran_id_dapodik' => $data->pembelajaran_id,
                        'induk_pembelajaran_id' => ($induk) ? $induk_pembelajaran_id : NULL,
                        'semester_id' => $data->semester_id,
                        'sekolah_id'				=> $data->sekolah_id,
                        'rombongan_belajar_id'		=> $data->rombongan_belajar_id,
                        'guru_id'					=> $data->ptk_id,
                        'mata_pelajaran_id'			=> $data->mata_pelajaran_id,
                        'nama_mata_pelajaran'		=> $data->nama_mata_pelajaran,
                        'kkm'						=> 0,
                        'is_dapodik'				=> 1,
                        'last_sync'					=> now(),
                    ]
                );
                $this->proses_sync('Memperoses', 'pembelajaran', $i, count($dapodik), $user->sekolah_id);
                $bar->advance();
                $i++;
            }
        }
        $bar->finish();
        if($pembelajaran_id){
            Pembelajaran::where(function($query) use ($user, $semester, $pembelajaran_id){
                $query->where('sekolah_id', $user->sekolah_id);
                $query->where('semester_id', $semester->semester_id);
                $query->whereNotIn('pembelajaran_id', $pembelajaran_id);
            })->delete();
        }
    }
    private function insert_mata_pelajaran($data, $user, $semester){
        $jurusan = Jurusan::find($data->jurusan_id);
        if($jurusan){
            Mata_pelajaran::updateOrCreate(
                [
                    'mata_pelajaran_id' => $data->mata_pelajaran_id,
                ],
                [
                    'jurusan_id' 				=> $data->jurusan_id,
                    'nama'						=> $data->nama,
                    'pilihan_sekolah'			=> $data->pilihan_sekolah,
                    'pilihan_kepengawasan'		=> $data->pilihan_kepengawasan,
                    'pilihan_buku'				=> $data->pilihan_buku,
                    'pilihan_evaluasi'			=> $data->pilihan_evaluasi,
                    'deleted_at'				=> $data->expired_date,
                    'last_sync'					=> now(),
                ]
            );
        }
    }
    private function simpan_kurikulum($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'pembelajaran', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $this->insert_kurikulum($data, $user, $semester);
            $this->proses_sync('Memperoses', 'kurikulum', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    private function insert_kurikulum($data, $user, $semester){
        $jurusan = NULL;
        if($data->jurusan_id){
            $jurusan = Jurusan::find($data->jurusan_id);
        }
        Kurikulum::updateOrCreate(
            [
                'kurikulum_id' => $data->kurikulum_id
            ],
            [
                'nama_kurikulum'			=> $data->nama_kurikulum,
                'mulai_berlaku'				=> $data->mulai_berlaku,
                'sistem_sks'				=> $data->sistem_sks,
                'total_sks'					=> $data->total_sks,
                'jenjang_pendidikan_id'		=> $data->jenjang_pendidikan_id,
                'jurusan_id'				=> ($jurusan) ? $data->jurusan_id : NULL,
                'deleted_at'				=> $data->expired_date,
                'last_sync'					=> now(),
            ]
        );
    }
    private function simpan_anggota_ekskul($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'anggota_ekskul', $i, count($dapodik), $user->sekolah_id);
        $anggota_rombel_id = [];
        foreach($dapodik as $data){
            $anggota_rombel_id[] = $data->anggota_rombel_id;
            $pd = Peserta_didik::find($data->peserta_didik_id);
            $rombel = Rombongan_belajar::find($data->rombongan_belajar_id);
            if($pd && $rombel){
                $this->simpan_anggota_rombel($data, $user, $semester, NULL);
            }
            $this->proses_sync('Memperoses', 'anggota_ekskul', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
        if($anggota_rombel_id){
            Anggota_rombel::where(function($query) use ($anggota_rombel_id, $user, $semester){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 51);
                });
                $query->where('sekolah_id', $user->sekolah_id);
                $query->where('semester_id', $semester->semester_id);
                $query->whereNotIn('anggota_rombel_id', $anggota_rombel_id);
            })->delete();
        }
    }
    private function simpan_anggota_rombel_pilihan($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'anggota_rombel_pilihan', $i, count($dapodik), $user->sekolah_id);
        $anggota_rombel_id = [];
        foreach($dapodik as $data){
            $anggota_rombel_id[] = $data->anggota_rombel_id;
            $pd = Peserta_didik::find($data->peserta_didik_id);
            $rombel = Rombongan_belajar::find($data->rombongan_belajar_id);
            if($pd && $rombel){
                $this->simpan_anggota_rombel($data, $user, $semester, NULL);
                $this->proses_sync('Memperoses', 'anggota_rombel_pilihan', $i, count($dapodik), $user->sekolah_id);
                $bar->advance();
                $i++;
            }
        }
        $bar->finish();
        if($anggota_rombel_id){
            Anggota_rombel::where(function($query) use ($anggota_rombel_id, $user, $semester){
                $query->whereHas('rombongan_belajar', function($query){
                    $query->where('jenis_rombel', 16);
                });
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
    private function simpan_dudi($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'dudi', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $a = Dudi::withTrashed()->updateOrCreate(
                [
                    'dudi_id' => $data->dudi_id
                ],
                [
                    'dudi_id_dapodik' => $data->dudi_id,
                    'sekolah_id'		=> $data->sekolah_id,
                    'nama'				=> $data->nama,
                    'bidang_usaha_id'	=> $data->bidang_usaha_id,
                    'nama_bidang_usaha'	=> $data->nama_bidang_usaha,
                    'alamat_jalan'		=> $data->alamat_jalan,
                    'rt'				=> $data->rt,
                    'rw'				=> $data->rw,
                    'nama_dusun'		=> $data->nama_dusun,
                    'desa_kelurahan'	=> $data->desa_kelurahan,
                    'kode_wilayah'		=> $data->kode_wilayah,
                    'kode_pos'			=> $data->kode_pos,
                    'lintang'			=> $data->lintang,
                    'bujur'				=> $data->bujur,
                    'nomor_telepon'		=> $data->nomor_telepon,
                    'nomor_fax'			=> $data->nomor_fax,
                    'email'				=> $data->email,
                    'website'			=> $data->website,
                    'npwp'				=> $data->npwp,
                    'last_sync'			=> now(),
                ]
            );
            Mou::withTrashed()->updateOrCreate(
                [
                    'mou_id' => $data->mou_id
                ],
                [
                    'mou_id_dapodik' => $data->mou_id,
                    'id_jns_ks'			=> $data->id_jns_ks,
                    'dudi_id'			=> $data->dudi_id,
                    'dudi_id_dapodik'	=> $data->dudi_id,
                    'sekolah_id'		=> $data->sekolah_id,
                    'nomor_mou'			=> $data->nomor_mou,
                    'judul_mou'			=> $data->judul_mou,
                    'tanggal_mulai'		=> $data->tanggal_mulai,
                    'tanggal_selesai'	=> ($data->tanggal_selesai) ? $data->tanggal_selesai : date('Y-m-d'),
                    'nama_dudi'			=> $data->nama_dudi,
                    'npwp_dudi'			=> $data->npwp_dudi,
                    'nama_bidang_usaha'	=> $data->nama_bidang_usaha,
                    'telp_kantor'		=> $data->telp_kantor,
                    'fax'				=> $data->fax,
                    'contact_person'	=> $data->contact_person,
                    'telp_cp'			=> $data->telp_cp,
                    'jabatan_cp'		=> $data->jabatan_cp,
                    'last_sync'			=> now(),
                ]
            );
            foreach($data->akt_pd as $akt_pd){
                $create_akt_pd = Akt_pd::withTrashed()->updateOrCreate(
                    [
                        'akt_pd_id' => $akt_pd->id_akt_pd
                    ],
                    [
                        'akt_pd_id_dapodik' => $akt_pd->id_akt_pd,
                        'sekolah_id'	=> $user->sekolah_id,
                        'mou_id'		=> $data->mou_id,
                        'id_jns_akt_pd'	=> $akt_pd->id_jns_akt_pd,
                        'judul_akt_pd'	=> $akt_pd->judul_akt_pd,
                        'sk_tugas'		=> ($akt_pd->sk_tugas) ? $akt_pd->sk_tugas : '-',
                        'tgl_sk_tugas'	=> $akt_pd->tgl_sk_tugas,
                        'ket_akt'		=> $akt_pd->ket_akt,
                        'a_komunal'		=> $akt_pd->a_komunal,
                        'last_sync'		=> now(),
                    ]
                );
                if($akt_pd->anggota_akt_pd){
                    foreach($akt_pd->anggota_akt_pd as $anggota_akt_pd){
                        if($anggota_akt_pd->registrasi_peserta_didik){
                            $find = Peserta_didik::find($anggota_akt_pd->registrasi_peserta_didik->peserta_didik_id);
                            if($find){
                                $create_anggota_akt_pd = Anggota_akt_pd::withTrashed()->updateOrCreate(
                                    [
                                        'anggota_akt_pd_id' => $anggota_akt_pd->id_ang_akt_pd,
                                    ],
                                    [
                                        'id_ang_akt_pd' => $anggota_akt_pd->id_ang_akt_pd,
                                        'sekolah_id'		=> $user->sekolah_id,
                                        'akt_pd_id'			=> $akt_pd->id_akt_pd,
                                        'peserta_didik_id'	=> $anggota_akt_pd->registrasi_peserta_didik->peserta_didik_id,
                                        'nm_pd'				=> $anggota_akt_pd->nm_pd,
                                        'nipd'				=> $anggota_akt_pd->nipd,
                                        'jns_peran_pd'		=> $anggota_akt_pd->jns_peran_pd,
                                        'last_sync'			=> now(),
                                    ]
                                );
                            }
                        }
                    }
                }
                if($akt_pd->bimbing_pd){
                    foreach($akt_pd->bimbing_pd as $bimbing_pd){
                        $find = Guru::withTrashed()->find($bimbing_pd->ptk_id);
                        if($find){
                            $create_bimbing_pd = Bimbing_pd::withTrashed()->updateOrCreate(
                                [
                                    'bimbing_pd_id' => $bimbing_pd->id_bimb_pd
                                ],
                                [
                                    'id_bimb_pd' => $bimbing_pd->id_bimb_pd,
                                    'sekolah_id'		=> $user->sekolah_id,
                                    'akt_pd_id'			=> $akt_pd->id_akt_pd,
                                    'guru_id'			=> $bimbing_pd->ptk_id,
                                    'ptk_id'			=> $bimbing_pd->ptk_id,
                                    'urutan_pembimbing'	=> $bimbing_pd->urutan_pembimbing,
                                    'last_sync'			=> now(),
                                ]
                            );
                        }
                    }
                }
            }
            $this->proses_sync('Memperoses', 'dudi', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
    public function simpan_mata_pelajaran($dapodik, $user, $semester){
        $i=1;
        $bar = $this->output->createProgressBar(count($dapodik));
        $bar->start();
		$this->proses_sync('Memperoses', 'mata_pelajaran', $i, count($dapodik), $user->sekolah_id);
        foreach($dapodik as $data){
            $this->insert_mata_pelajaran($data, $user, $semester);
            $this->proses_sync('Memperoses', 'mata_pelajaran', $i, count($dapodik), $user->sekolah_id);
            $bar->advance();
            $i++;
        }
        $bar->finish();
    }
}
